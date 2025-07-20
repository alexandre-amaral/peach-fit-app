<?php

namespace App\Services\Panel\User;

use App\Mail\Panel\User\NewAdminRegisterMail;
use App\Models\User;
use App\Services\Services;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;



class UpdateUserService extends Services
{
    /**
     * Register user.
     *
     * @param array $data
     * @return int
     * \App\Services\Panel
     */
    public function updateUser(Request $request, int $userid): int
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ], [
            'name.required' => 'O campo nome é obrigatório',
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
        ]);


        $data = $request->all();
        unset($data['_token'], $data['avatar']);


        $user = User::findOrFail($userid)->update($data);

        return true;
    }
}
