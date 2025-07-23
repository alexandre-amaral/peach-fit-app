<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Services\General\LoginAppService;
use App\Services\Panel\User\GetUserService;

class LoginAppController extends Controller
{

     /**
     * Envia código de 2FA para o e-mail do usuário
     * 
     * @param Request $request
     * @return jsonResponse
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');

        //Valida se o e-mail tem cadastro
        try {
            $user = (new GetUserService())->getUserByEmail($email);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'E-mail de usario nao cadastrado.'], 404);
        }

        $response = (new LoginAppService())->sendLoginCode($email);

        if(!$response['success']) {
            return response()->json(['error' => 'Erro ao enviar codigo.', 'details' => $response['error'] ?? 'Erro desconhecido'], 500);
        }

        $responseData = ['message' => 'Codigo enviado por e-mail com sucesso.'];
        
        // 🔧 DESENVOLVIMENTO: Incluir código na resposta quando usando log driver
        if (config('mail.default') === 'log' && isset($response['development_code'])) {
            $responseData['development_code'] = $response['development_code'];
        }

        return response()->json($responseData, 200);

    }  
    
    /**
     * Valida se o código digitado é o que foi enviado por e-mail
     * 
     * @param Request $request
     * @return jsonResponse
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6'
        ]);

        $email = $request->email;
        $code = $request->code;

        $response = (new LoginAppService())->verifyCode($email, $code);

        if ($response) {
            $token = (new LoginAppService())->authenticateAndGenerateToken($email);

            if ($token) {
                return response()->json([
                    'message' => 'Login bem-sucedido.',
                    'token' => $token,
                    'token_type' => 'Bearer'
                ], 200);
            } else {
                return response()->json(['error' => 'Não foi possível autenticar o usuário ou gerar o token.'], 500);
            }
        }

        return response()->json(['error' => 'Codigo inválido ou expirado.'], 422);
    }

}
