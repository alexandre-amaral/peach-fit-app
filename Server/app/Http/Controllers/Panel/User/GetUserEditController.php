<?php

namespace App\Http\Controllers\Panel\User;

use App\Services\Panel\User\GetUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Panel\User\DeleteUserService;

class GetUserEditController extends Controller
{
    public function index(Request $request, $userid)
    {
        try {
            $user = (new GetUserService())->getUser($userid);
            return view('panel.pages.user.edit', compact('user'));
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir usuários: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os usuários.']);
        }
    }
}
