<?php

namespace App\Services\Panel\PersonalTrainer;

use App\Models\PersonalTrainer;
use App\Models\User;

class DeletePersonalTrainerService
{
    public function delete($personalId)
    {
        $personal = PersonalTrainer::find($personalId);
        $user = User::find($personal->user_id);
        $personal->delete();
        $user->delete();
    }
}