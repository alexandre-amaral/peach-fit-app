<?php

namespace App\Http\Controllers\Panel\Self;

use App\Http\Controllers\Controller;
use App\Services\Panel\Self\UserLogoutService;

class UserLogoutController extends Controller
{
    public function index()
    {
        (new UserLogoutService())->logoutUser();

        return redirect()->route('general.view.login')->with('success', 'Usuário saiu com sucesso');
    }
}
