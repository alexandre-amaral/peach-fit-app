<?php

namespace App\Services\Panel\Customers;

use App\Models\Customer;

class GetCustomersService
{
    public function getCustomers()
    {
        $customers = Customer::join('users', 'users.id', '=', 'customers.user_id')
            ->select('customers.*','users.email','users.name')
            ->paginate(10);
        
        return $customers;
    }

    public function getCustomer($id)
    {
        return Customer::join('users','users.id','=','customers.user_id')
        ->where('customers.id','=', $id)
        ->select('customers.*','users.email','users.name')
        ->first();
    }
}