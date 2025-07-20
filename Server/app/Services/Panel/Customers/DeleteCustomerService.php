<?php

namespace App\Services\Panel\Customers;

use App\Models\Customer;
use App\Models\User;

class DeleteCustomerService
{
    public function delete($customerId)
    {
        $customer = Customer::find($customerId);
        $user = User::find($customer->user_id);
        $customer->delete();
        $user->delete();
    }
}