<?php

namespace App\Services\General;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Services\Services;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class ForgotPasswordService extends Services
{

    public bool $status = false;
    public string $newPassword;

    public function __construct(private string $email)
    {
        $this->newPassword = Str::random(10);
        $this->forgot();
    }


    private function forgot()
    {
        $user = User::where('email', $this->email)->first();


        $data = [
            'name' => $user->name,
            'password' => $this->newPassword
        ];

        $user->update(['password' => $this->newPassword]);

        Mail::to($this->email)->send(new ForgotPasswordMail($data));

        $this->status = true;
    }
}
