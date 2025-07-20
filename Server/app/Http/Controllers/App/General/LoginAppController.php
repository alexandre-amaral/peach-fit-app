<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
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

        //Valida se o e-mail tem cadastro
        try {
            $user = (new GetUserService())->getUserByEmail($request->input('email'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'E-mail de usario nao cadastrado.'], 404);
        }

        $response = (new LoginAppService())->sendLoginCode($request->input('email'));

        if(!$response) {
            return response()->json(['error' => 'Erro ao enviar codigo.']);
        }
        
        return response()->json('Codigo enviado por e-mail com sucesso.', 200);

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

        $response = (new LoginAppService())->verifyCode($request->email, $request->code);


        if ($response) {
            $token = (new LoginAppService())->authenticateAndGenerateToken($request->email);

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

        return response()->json('Codigo inválido ou expirado.', 422);
    }

}
