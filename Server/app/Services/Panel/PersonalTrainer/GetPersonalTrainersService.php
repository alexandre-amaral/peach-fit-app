<?php

namespace App\Services\Panel\PersonalTrainer;

use App\Models\PersonalTrainer;
use App\Services\Services;

class GetPersonalTrainersService extends Services
{
    public function GetPersonalTrainers($search = false)
    {
        if ($search) {
            return PersonalTrainer::join("users", "personal_trainers.user_id", "=", "users.id")
                ->where("users.name", "LIKE", "%". $search ."%")
                ->select('personal_trainers.*', 'users.name as name', 'users.email as email');
        }
    
        return PersonalTrainer::join('users', 'personal_trainers.user_id', 'users.id')
            ->select('personal_trainers.*', 'users.name as name', 'users.email as email')
            ->paginate(10);
    }

    public function GetPersonalTrainer($id)
    {
        return PersonalTrainer::query()
        ->join('users', 'users.id', '=', 'personal_trainers.user_id')
        ->where('personal_trainers.id', $id)
        ->select('personal_trainers.*', 'users.name as name', 'users.email as email')
        ->first();
    }
}