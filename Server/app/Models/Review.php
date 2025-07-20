<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\TrainingSession;
use App\Models\PersonalTrainer;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'customer_id',
        'personal_id',
        'training_session_id',
        'reviewed_by',
        'rating',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function personal()
    {
        return $this->belongsTo(PersonalTrainer::class, 'persona_id');
    }

    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }
}
