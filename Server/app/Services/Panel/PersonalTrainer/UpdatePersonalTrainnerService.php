<?php

namespace App\Services\Panel\PersonalTrainer;

use App\Models\IbgeState;
use App\Models\PersonalTrainer;
use App\Models\User;

class UpdatePersonalTrainnerService
{
    public function updatePersonalTrainer($data, $personalTrainerId)
    {
        $personalTrainer = PersonalTrainer::find($personalTrainerId);
        $user = User::find($personalTrainer->user_id);
        $state = IbgeState::where('ibge_id', '=',$data['state'])->first();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        $personalTrainer->cpf = $data['cpf'];
        $personalTrainer->speciality = $data['speciality'];
        $personalTrainer->tel = preg_replace('/[^0-9]/', '', $data['tel']);
        $personalTrainer->gender = $data['gender'];
        $personalTrainer->hourly_rate = (float) str_replace(',', '.', preg_replace('/[^0-9,]/', '', $data['rate']));
        $personalTrainer->state_id = $state->id;
        $personalTrainer->ibge_state_id = $state->ibge_id;
        $personalTrainer->ibge_city_id = $data['city'];
        $personalTrainer->save();

        return $personalTrainer;
    }
}