<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalPaypalCredential extends Model
{
    use HasFactory;

    protected $table = 'personal_paypal_credentials';

    protected $fillable = [
        'personal_id',
        'payment_email',
    ];

    public function personalTrainer(): BelongsTo
    {
        return $this->belongsTo(PersonalTrainer::class, 'personal_id');
    }
}
