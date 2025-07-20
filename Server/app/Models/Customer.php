<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = "customers";

    protected $fillable = [
        'user_id',
        'ibge_state_id',
        'ibge_city_id',
        'cpf',
        'tel',
        'preferences' 
    ];

    protected $casts = [
    'preferences' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}