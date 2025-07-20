<?php

namespace App\Http\Controllers\Panel\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Panel\User\GetUsersService;

class GetUsersController extends Controller
{
    public function index(Request $request)
    {
        try {
            $users = (new GetUsersService())->getUsers();
            return view('panel.pages.user.index', compact('users'));
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir usuários: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os usuários.']);
        }
    }

    public function addUserView()
    {
        return view('panel.pages.user.add');
    }
}
