<?php

namespace App\Http\Controllers\Panel\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Panel\User\GetUserService;
use App\Services\Panel\User\GetUsersService;
use App\Services\General\ForgotPasswordService;

class ResetUserPasswordController extends Controller
{
    public function resetPassword($userid)
    {
        try {
            $user = (new GetUserService())->getUser($userid);
            new ForgotPasswordService($user->email);
            return redirect()->route('admin.view.edit', ['userid' => $userid])->with('success', 'Senha resetada com sucesso.');
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir usuários: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os usuários.']);
        }
    }
}
