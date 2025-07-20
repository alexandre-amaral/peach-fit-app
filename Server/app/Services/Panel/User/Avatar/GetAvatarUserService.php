<?php

namespace App\Services\Panel\User\Avatar;

use App\Models\User;

class GetAvatarUserService
{
    /**
    * Busca o avatar do usuário
    *
    * @param int $userId
    */
    
    public function getAvatar(int $userId): ?string
    {
        $user = User::find($userId);

        return $user?->avatar;
    }
}
