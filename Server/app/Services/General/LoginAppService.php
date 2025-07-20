<?php
namespace App\Services\General;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\Login2FAMail;
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
    

    public function sendLoginCode($email) {
    
     try { 
        $code = rand(100000, 999999); 

        Cache::put("email_verification_code_{$email}", $code, now()->addMinutes(10));

       $data = ['code' => $code];

        Mail::to($email)->send(new Login2FAMail($data));

        return true;

        } catch (\Exception $e) {

            return false; 
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
        $cachedCode = Cache::get("email_verification_code_{$email}");

        if ($cachedCode && $code == $cachedCode) {
            Cache::forget("email_verification_code_{$email}");
            return true;
        }

        return false;
    }

    public function authenticateAndGenerateToken($email)
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return null;
        }

        $token = Str::random(60); 

        $isHash = LoginUsersCredential::where('user_id', $user->id)->first();
        
        if($isHash) 
        {
            LoginUsersCredential::where('user_id', $user->id)->delete();
        }

        LoginUsersCredential::create([
            'email' => $email,
            'user_id' => $user->id,
            'token' => $token
        ]);

        Auth::login($user);

        return $token;
    }
}