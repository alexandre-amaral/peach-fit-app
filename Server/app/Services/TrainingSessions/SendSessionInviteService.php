<?php

namespace App\Services\TrainingSessions;

use App\Models\TrainingSession;
use App\Models\Customer; 
use App\Models\PersonalTrainer; 
use App\Models\User; 
use App\Events\TrainingSessionCreated;
use App\Services\Notifications\CreateNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSessionInviteService
{
    /**
     * Manda convite de treino de um cliente para um personal.
     *
     * @param int $personalId
     * @param string $proposedDatetime
     * @param float $duration
     * @return TrainingSession
     */

    public function inviteSession(int $personalId, string $proposedDatetime, float $duration)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $customer = Customer::where('user_id', $user->id)->first();

            if (!$customer) {
                throw new \Exception("Usuário autenticado não é um cliente válido.");
            }

            $personal = PersonalTrainer::find($personalId);
            
            if (!$personal) {
                throw new \Exception("Personal Trainer com ID {$personalId} não encontrado.");
            }

            $total_price = floatval($duration*$personal->hourly_rate);

            $session = TrainingSession::create([
                'customer_id' => $customer->id,
                'personal_id' => $personalId,
                'status' => 'pending',
                'proposed_datetime' => $proposedDatetime,
                'payment_status' => 'pending',
                'duration' => $duration,
                'total_price'=> $total_price
            ]);

            DB::commit();
            $user = PersonalTrainer::find($personalId)?->user;

            if (!$user) {
                throw new \Exception('Usuário não encontrado para o personal trainer');
            }
            $msg = 'Você recebeu uma nova proposta de treino em ' . $session->proposed_datetime->format('d/m/Y H:i');
            
            event(new TrainingSessionCreated($session, $msg));
            
            $notification = new CreateNotificationService(
                     $user, 
                     'fit',
                     'Proposta de treino',
                     $msg
                 );
            $notification->saveNotification();

            return $session;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erro ao convidar sessão: " . $e->getMessage());
            throw $e;
        }
    }
}