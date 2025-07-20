<?php

namespace App\Observers;

use App\Models\TrainingSession;
use App\Models\PersonalSchedule; 
use Illuminate\Support\Facades\Log;

class TrainingSessionObserver
{
      
    /**
     * Observa a training session para garantir que a agenda do personal seja bloqueada
     * @param int $id
     * @return jsonResponse
     */
    public function updated(TrainingSession $trainingSession): void
    {
        if ($trainingSession->isDirty('status')) {

            if($trainingSession->status === 'accepted_by_personal') {
                
                $schedule = PersonalSchedule::where('personal_id', $trainingSession->personal_id)
                                        ->where('datetime', $trainingSession->proposed_datetime)
                                        ->first();
                if ($schedule) {
                    $schedule->update(['status' => 'blocked']);
                }
                
            } else if($trainingSession->status === 'time_confirmed') {
                
                $schedule = PersonalSchedule::where('personal_id', $trainingSession->personal_id)
                                        ->where('datetime', $trainingSession->proposed_datetime)
                                        ->first();
                if ($schedule) {
                    $schedule->update(['status' => 'taken']);
                }
            }
          
        }
    }

}
