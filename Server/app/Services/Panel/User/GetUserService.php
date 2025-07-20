<?php

namespace App\Services\Panel\User;

use App\Models\User;
use App\Services\Services;



class GetUserService extends Services
{
    public function getUser($userid)
    {
        return User::findOrFail($userid);
    }

    public function getUserByEmail(string $email)
    {
        return User::where('email', $email)->firstOrFail();
    }
}
