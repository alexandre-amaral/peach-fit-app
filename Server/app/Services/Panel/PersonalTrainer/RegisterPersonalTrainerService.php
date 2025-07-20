<?php

namespace App\Services\Panel\PersonalTrainer;

use App\Models\IbgeState;
use App\Models\PersonalTrainer;
use App\Models\User;
use App\Services\Panel\User\RegisterUserService;
use Illuminate\Http\Request;

class RegisterPersonalTrainerService
{
    public function register(Request $request)
    {
        $data = $request->all();
        $user = (new RegisterUserService())->registerUser($request, 'personal');
        $state = IbgeState::where('ibge_id', '=',$data['state'])->first();
        $dataPersonal = [
            'user_id' => $user,
            'cpf' => $data['cpf'],
            'speciality' => $data['speciality'],
            'tel' => $data['tel'],
            'gender' => $data['gender'],
            'hourly_rate' => $data['rate'],
            'state_id' => $state->id,
            'ibge_state_id' => $state->ibge_id,
            'ibge_city_id' => $data['city'],
        ];

        return PersonalTrainer::create($dataPersonal);
    }
}
