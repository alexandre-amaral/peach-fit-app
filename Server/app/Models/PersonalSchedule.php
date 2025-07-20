<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TrainingSession;
use App\Models\PersonalTrainer;

class PersonalSchedule extends Model
{
    use HasFactory;

    protected $table = 'personal_schedules';

    protected $fillable = [
        'personal_id',
        'datetime',
        'status',
        'training_session_id',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function personalTrainer()
    {
        return $this->belongsTo(PersonalTrainer::class, 'personal_id');
    }

    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }
}
