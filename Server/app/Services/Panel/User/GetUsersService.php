<?php

namespace App\Services\Panel\User;

use App\Models\User;
use App\Services\Services;

class GetUsersService extends Services
{
    public function getUsers($search = false)
    {
        $users = User::class;


        if (!$search) {
            $users = $users::where('name', 'LIKE', '%' . $search . '%');
        }

        return $users->where('role', 'admin')->paginate(10);
    }
}
