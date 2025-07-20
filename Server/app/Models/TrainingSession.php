<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\PersonalTrainer;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'personal_id',
        'status',
        'proposed_datetime',
        'confirmed_datetime',
        'location',
        'payment_status',
        'duration', 
        'total_price'
    ];

    protected $casts = [
        'proposed_datetime' => 'datetime',
        'confirmed_datetime' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function personal()
    {
        return $this->belongsTo(PersonalTrainer::class, 'personal_id');
    }
}