<?php

namespace App\Services\Panel\User\Avatar;

use App\Models\User;
use App\Services\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;


class SaveAvatarUserService extends Services
{
    public function __construct(private int $userid, private UploadedFile $file)
    {
        $this->registerAvatar();
    }
    public function registerAvatar()
    {
        $user = User::findOrFail($this->userid);

        $extension = $this->file->getClientOriginalExtension();


        $newFilename = Str::slug($user->name) . '_' . time() . '.' . $extension;

        $this->file->storeAs('users/avatars', $newFilename, 'public');


        $user->update(['avatar' => $newFilename]);
    }
}
