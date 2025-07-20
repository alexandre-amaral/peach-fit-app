<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Panel\Self\UserLogoutService;
use App\Models\LoginUsersCredential;

class LogoutAppController extends Controller
{
    /**
     * Realiza o logout do usuário
     * 
     * @param Request $request
     * @return jsonResponse
     */
    public function logoutUser(Request $request) {
    
       $token = $request->bearerToken();

        if (Auth::check()) {
            if ($token) {
                $deleted = LoginUsersCredential::where('user_id', Auth::id())
                                            ->where('token', $token)
                                            ->delete();

                if ($deleted) {
                    Auth::logout();

                    return response()->json(['message' => 'Logout realizado com sucesso.'], 200);
                }
            }
            return response()->json(['message' => 'Token não encontrado para logout.'], 400);
        }

        return response()->json(['message' => 'Não autenticado para realizar logout.'], 401);
    }
}