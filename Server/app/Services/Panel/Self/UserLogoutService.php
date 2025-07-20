<?php

namespace App\Services\Panel\Self;

use Illuminate\Support\Facades\Auth;


class UserLogoutService
{
    public function logoutUser()
    {
        Auth::logout();
    }
}
