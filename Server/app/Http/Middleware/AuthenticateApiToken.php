<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginUsersCredential; 
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
   
    /**
    * Valida se o Authorization token está presente na requisição 
    * garantindo a segurança da API
    * @param Request $request
    * @param Closure $next
    * @return jsonResponse
    */
    
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token de autenticação não fornecido.'], 401);
        }

        $credential = LoginUsersCredential::where('token', $token)
                                          ->first();

        if (!$credential) {
            return response()->json(['message' => 'Token de autenticação inválido ou expirado.'], 401);
        }
       
        Auth::loginUsingId($credential->user_id);

      
        return $next($request);
    }
}