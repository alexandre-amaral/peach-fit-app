<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Customer;
use App\Models\PersonalTrainer;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('customer.{customerId}', function ($user, $customerId) {
    $customer = Customer::where('user_id', $user->id)->first();
    return $customer && (int) $customer->id === (int) $customerId;
});

Broadcast::channel('personal.{personalId}', function ($user, $personalId) {
    $personal = PersonalTrainer::where('user_id', $user->id)->first();
    return $personal && (int) $personal->id === (int) $personalId;
});

