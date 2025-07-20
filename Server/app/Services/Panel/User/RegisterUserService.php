<?php

namespace App\Services\Panel\User;

use App\Mail\Panel\User\NewAdminRegisterMail;
use App\Models\User;
use App\Services\Services;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;



class RegisterUserService extends Services
{
    private string $newPassword;
    public function __construct()
    {
        $this->newPassword = Str::random(10);
    }

    /**
     * Register user.
     *
     * @param array $data
     * @return int
     * \App\Services\Panel
     */
    public function registerUser(Request $request, string $role): int
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

        $data['password'] = $this->newPassword;
        $data['role'] = $role;

        $user = User::create($data);

        Mail::to($request->email)->send(new NewAdminRegisterMail($user, $this->newPassword));
        return $user->id;
    }
}
