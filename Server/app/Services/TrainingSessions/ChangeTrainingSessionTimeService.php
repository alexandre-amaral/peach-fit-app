<?php

namespace App\Services\TrainingSessions;

use App\Models\TrainingSession;
use App\Models\User;
use App\Models\Customer;
use App\Models\PersonalTrainer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Events\TrainingSessionChanged;
use App\Services\Notifications\CreateNotificationService;

class ChangeTrainingSessionTimeService
{
    /**
     * Personal altera o horário da sessão de treino.
     *
     * @param int $sessionId
     * @param string $proposedDatetime
     * @return TrainingSession
     */
    public function changeTime(int $sessionId, string $proposedDatetime)
    {
        DB::beginTransaction();
        try {
            $session = TrainingSession::find($sessionId);

            if (!$session) {
                throw new \Exception("Sessão de treino não encontrada.");
            }

            $user = Auth::user();
            $personal = PersonalTrainer::where('user_id', $user->id)->first();

            $session->update([
                'proposed_datetime' => $proposedDatetime,
                'status' => 'time_change',
            ]);

            DB::commit();

            $msg = 'O Personal Trainer sugeriu um novo horário! ' . $session->proposed_datetime->format('d/m/Y H:i');

            event(new TrainingSessionChanged($session, $msg));

            $user = Customer::find($session->customer_id)->user;

            $notification = new CreateNotificationService(
                $user,
                'fit',
                'Alteração de horário de treino solicitada!',
                $msg
            );

            $notification->saveNotification();

            return $session;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao alterar horário da sessão: " . $e->getMessage());
            throw $e;
        }
    }
}
