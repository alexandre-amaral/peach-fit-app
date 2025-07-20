<?php

namespace App\Services\Panel\User\Avatar;

use App\Models\User;
use App\Services\Services;
use Illuminate\Support\Facades\Storage;


class DeleteAvatarUserService extends Services
{

    public function __construct(private int $userid)
    {
        $this->deleteAvatar();
    }


    private function deleteAvatar()
    {
        $filename = User::findOrFail($this->userid)->avatar;

        Storage::disk('public')->delete('users/avatars/' . $filename);
    }
}
