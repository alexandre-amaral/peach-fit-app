<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\General\LoginUserService;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('general.pages.login');
    }


    public function doLogin(Request $request)
    {
        try {
            if ((new LoginUserService($request))->loginUser()) {
                return redirect()->intended(route('admin.view.dashboard'));
            }

            return back()->withErrors(['email' => 'Email ou senha inválidos.'])->onlyInput('email');
        } catch (\Exception $e) {
            Log::emergency('Falha ao realizar login: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Falha ao fazer login no sistema. Contate o administrador'])->onlyInput('email');
        }
    }
}
