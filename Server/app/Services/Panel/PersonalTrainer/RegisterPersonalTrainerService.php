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
    
    // ✅ NOVO MÉTODO ESPECÍFICO PARA API
    public function registerForApi(Request $request)
    {
        $data = $request->all();
        
        // Criar usuário diretamente com senha fornecida
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']), // ✅ Usar senha fornecida
            'role' => 'personal',
            'email_verified_at' => now(), // ✅ Marcar como verificado para API
        ];
        
        $user = User::create($userData);
        
        // Buscar estado (se necessário)
        $state = null;
        if (isset($data['state'])) {
            $state = IbgeState::where('ibge_id', '=', $data['state'])->first();
        }
        
        // Criar personal trainer
        $personalData = [
            'user_id' => $user->id,
            'cpf' => $data['cpf'],
            'speciality' => $data['speciality'],
            'tel' => $data['tel'],
            'gender' => $data['gender'],
            'hourly_rate' => $data['rate'],
            'state_id' => $state ? $state->id : null,
            'ibge_state_id' => $data['state'] ?? null,
            'ibge_city_id' => $data['city'] ?? null,
        ];

        $personal = PersonalTrainer::create($personalData);
        
        // ✅ Retornar dados no formato esperado pela API
        return [
            'status' => true,
            'message' => 'Personal Trainer cadastrado com sucesso!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 2, // Personal Trainer
                'personal_id' => $personal->id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ];
    }
}
