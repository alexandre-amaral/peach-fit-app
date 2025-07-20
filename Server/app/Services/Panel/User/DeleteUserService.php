<?php

namespace App\Services\Panel\User;

use App\Models\User;
use App\Services\Services;

class DeleteUserService extends Services
{
    public function __construct(private int $userid)
    {
        $this->deleteUser();
    }


    private function deleteUser()
    {

        User::destroy($this->userid);
    }
}
