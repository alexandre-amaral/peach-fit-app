<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutPersonal extends Model
{
    use HasFactory;

    protected $table = 'payout_personals';

    protected $fillable = [
        'personal_id',
        'paypal_batch_id',
        'status',
        'total_amount',
        'currency',
        'items_sent',
        'paypal_response',
    ];

    protected $casts = [
        'items_sent' => 'array',
        'paypal_response' => 'array',
        'total_amount' => 'decimal:2',
    ];

    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class, 'personal_id');
    }
}