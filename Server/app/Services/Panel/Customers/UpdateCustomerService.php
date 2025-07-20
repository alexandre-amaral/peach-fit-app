<?php

namespace App\Services\Panel\Customers;

use App\Models\Customer;
use App\Models\User;

class UpdateCustomerService
{
    public function updateCustomer($data, $customerId)
    {
        $customer = Customer::find($customerId);
        $user = User::find($customer->user_id);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        $customer->cpf = $data['cpf'];
        $customer->tel = preg_replace('/[^0-9]/', '', $data['tel']);
        $customer->ibge_state_id = $data['state'];
        $customer->ibge_city_id = $data['city'];
        $customer->save();

        return $customer;
    }
}