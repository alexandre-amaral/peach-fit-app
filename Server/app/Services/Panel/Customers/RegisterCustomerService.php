<?php

namespace App\Services\Panel\Customers;

use App\Models\Customer;
use App\Models\User;
use App\Services\Panel\User\RegisterUserService;
use Illuminate\Http\Request;

class RegisterCustomerService
{
    public function register(Request $request)
    {
        $data = $request->all();
        $user = (new RegisterUserService())->registerUser($request, 'customer');
        $dataCustomer = [
            'user_id' => $user,
            'ibge_state_id' => $data['state'],
            'ibge_city_id' => $data['city'],
            'cpf' => $data['cpf'],
            'tel' => $data['tel'],
            'preferences' => $data['preferences'] ?? null,
        ];

        return Customer::create($dataCustomer);
    }
    
    // ✅ NOVO MÉTODO ESPECÍFICO PARA API
    public function registerForApi(Request $request)
    {
        $data = $request->all();
        
        // Criar usuário diretamente com senha fornecida (não gerar senha aleatória)
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']), // ✅ Usar senha fornecida
            'role' => 'customer',
            'email_verified_at' => now(), // ✅ Marcar como verificado para API
        ];
        
        $user = User::create($userData);
        
        // Criar customer
        $customerData = [
            'user_id' => $user->id,
            'ibge_state_id' => $data['state'],
            'ibge_city_id' => $data['city'],
            'cpf' => $data['cpf'],
            'tel' => $data['tel'],
            'preferences' => $data['preferences'] ?? null,
        ];

        $customer = Customer::create($customerData);
        
        // ✅ Retornar dados no formato esperado pela API
        return [
            'status' => true,
            'message' => 'Cliente cadastrado com sucesso!',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'type' => 1, // Cliente
                'customer_id' => $customer->id,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]
        ];
    }
}