<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LoginUsersCredential extends Model
{   
 
    protected $table = "login_users_credentials";

    protected $fillable = [
        'email',
        'user_id',
        'token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}