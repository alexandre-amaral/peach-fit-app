<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalTrainer extends Model
{
    protected $table = "personal_trainers";
    
    protected $fillable = [
        'user_id',
        'cpf',	
        'speciality',	
        'tel',	
        'gender',	
        'hourly_rate',	
        'state_id',
        'ibge_state_id',	
        'ibge_city_id'	
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
