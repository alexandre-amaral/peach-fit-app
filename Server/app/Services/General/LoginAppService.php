<?php
namespace App\Services\General;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\Login2FAMail;
use App\Services\General\GmailService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\LoginUsersCredential;

class LoginAppService
{
    /**
     * Envia código de login gerado randomicamente para o cliente
     *
     * @param string $email
     * @return bool
     */
    public function sendLoginCode($email) 
    {
        try { 
            $code = rand(100000, 999999); 
            $cacheKey = "email_verification_code_{$email}";
            
            Log::info("Generating 2FA code", [
                'email' => $email,
                'expires_at' => now()->addMinutes(10)->toDateTimeString()
            ]);

            // Armazenar no cache por 10 minutos
            Cache::put($cacheKey, $code, now()->addMinutes(10));
            
            // Verificar se foi armazenado corretamente
            $storedCode = Cache::get($cacheKey);
            if ($storedCode != $code) {
                Log::error("Failed to store code in cache", [
                    'email' => $email
                ]);
                return false;
            }

            // 🚀 Enviar email usando CustomMailService com template HTML
            $emailResult = GmailService::send2FACode($email, $code);
            
            if (!$emailResult['success']) {
                Log::error("Failed to send 2FA email", [
                    'email' => $email,
                    'error' => $emailResult['error'] ?? 'Unknown error'
                ]);
                return ['success' => false, 'error' => 'Falha ao enviar email'];
            }

            Log::info("2FA email sent successfully", [
                'email' => $email,
                'method' => $emailResult['method'],
                'mailer' => config('mail.default')
            ]);

            // 🔧 DESENVOLVIMENTO: Mostrar código no log quando usando log driver
            if (config('mail.default') === 'log') {
                Log::info("🔧 CÓDIGO 2FA PARA DESENVOLVIMENTO", [
                    'email' => $email,
                    'code' => $code,
                    'message' => 'Código disponível nos logs para desenvolvimento'
                ]);
            }

            return ['success' => true];

        } catch (\Exception $e) {
            Log::error("Error sending 2FA code", [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return ['success' => false, 'error' => $e->getMessage()]; 
        }
    }

    /**
     * Valida o código de login digitado pelo usuário
     *
     * @param string $email
     * @param string $code
     * @return bool
     */
    public function verifyCode($email, $code)
    {
        $cacheKey = "email_verification_code_{$email}";
        
        Log::info("Validating 2FA code", [
            'email' => $email
        ]);

        $cachedCode = Cache::get($cacheKey);

        if ($cachedCode && $code == $cachedCode) {
            // Remover código do cache após uso
            Cache::forget($cacheKey);
            
            Log::info("2FA code validated successfully", [
                'email' => $email
            ]);
            
            return true;
        }

        Log::warning("Invalid or expired 2FA code", [
            'email' => $email
        ]);

        return false;
    }

    /**
     * Autentica o usuário e gera token de acesso
     *
     * @param string $email
     * @return string|null
     */
    public function authenticateAndGenerateToken($email)
    {
        Log::info("Generating authentication token", [
            'email' => $email
        ]);

        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::error("User not found for authentication", [
                'email' => $email
            ]);
            return null;
        }

        // Gerar token mais robusto
        $token = hash('sha256', Str::random(80) . time() . $user->id);

        // Remover tokens existentes do usuário
        $existingTokens = LoginUsersCredential::where('user_id', $user->id)->count();
        if ($existingTokens > 0) {
            LoginUsersCredential::where('user_id', $user->id)->delete();
            Log::info("Removed existing tokens", [
                'user_id' => $user->id,
                'count' => $existingTokens
            ]);
        }

        // Criar novo token
        $credential = LoginUsersCredential::create([
            'email' => $email,
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (!$credential) {
            Log::error("Failed to create authentication credential", [
                'email' => $email,
                'user_id' => $user->id
            ]);
            return null;
        }

        // Autenticar usuário na sessão
        Auth::login($user);

        Log::info("Authentication token generated successfully", [
            'email' => $email,
            'user_id' => $user->id
        ]);

        return $token;
    }
}