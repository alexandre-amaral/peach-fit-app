<?php

namespace App\Http\Controllers\Panel\User;

use App\Services\Panel\User\DeleteUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DeleteUserController extends Controller
{
    public function index(Request $request, $userid)
    {
        try {
            (new DeleteUserService($userid));
            return redirect()->route('admin.view.users')->with('success', 'Usuário removido com sucesso.');
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir usuários: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os usuários.']);
        }
    }
}
