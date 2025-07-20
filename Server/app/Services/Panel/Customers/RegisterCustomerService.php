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
            'preferences' => $data['preferences'],
        ];

        return Customer::create($dataCustomer);
    }
}