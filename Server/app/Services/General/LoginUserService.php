<?php


namespace App\Services\General;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserService
{

    public function __construct(private Request $request) {}

    public function loginUser()
    {
        $credentials = $this->request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um email válido.',
            'password.required' => 'O campo senha é obrigatório.',
        ]);

        if (Auth::attempt($credentials, $this->request->remember ?? false)) {
            $this->request->session()->regenerate();

            return true;
        }

        return false;
    }
}
