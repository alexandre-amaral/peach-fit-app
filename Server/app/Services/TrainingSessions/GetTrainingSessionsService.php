<?php

namespace App\Services\TrainingSessions;

use App\Models\TrainingSession;
use App\Models\PersonalTrainer;
use App\Events\TrainingSessionTimeConfirmed; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; 

class GetTrainingSessionsService
{
    /**
     * Busca sessões de treino
     */
       public function getTrainingSessions()
    {
        $trainingSessions = TrainingSession::paginate(10);

        
        return $trainingSessions;
    }

   /**
     * Busca sessões de treino por personal
     * @param int $id
     */
    public function getTrainingSessionsByPersonal($id)
    {
        $trainingSessions = TrainingSession::where('personal_id', 1)
              ->paginate(10);

        return $trainingSessions;
    }

    /**
     * Busca sessões de treino por cliente
     * @param int $id
     */
       public function getTrainingSessionsByCustomer($id)
    {
        $trainingSessions = TrainingSession::where('customer_id', $id)
            ->paginate(10);

        return $trainingSessions;
    }

}