<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payable_id',
        'payable_type',
        'amount',
        'currency',
        'fee_amount',
        'description',
        'paypal_order_id',
        'paypal_capture_id',
        'paypal_payment_id',
        'status',
        'status_details',
    ];

    // Relação com o usuário que fez o pagamento
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relação polimórfica para a entidade paga
    public function payable()
    {
        return $this->morphTo();
    }
}