<?php

namespace App\Services\TrainingSessions;

use App\Models\TrainingSession;
use App\Models\Customer;
use App\Models\PersonalTrainer;
use App\Events\TrainingSessionTimeConfirmed; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Notifications\CreateNotificationService;
use Carbon\Carbon; 

class ConfirmSessionInviteService
{
    /**
     * Cliente confirma o horário do treino
     *
     * @param int 
     * @param string 
     * @return TrainingSession
     */
    public function confirmSessionTime(int $sessionId)
    {
        DB::beginTransaction();
        try {
            $session = TrainingSession::find($sessionId);

            if (!$session) {
                throw new \Exception("Sessão de treino não encontrada.");
            }

            $user = Auth::user();
            $customer = Customer::where('user_id', $user->id)->first();

            if (!$customer || $session->customer_id !== $customer->id) {
                throw new \Exception("Você não tem permissão para confirmar este treino");
            }

            $session->update([
                'status' => 'time_confirmed',
                'confirmed_datetime' => $session->proposed_datetime,
            ]);

            DB::commit();

            $msg = 'O cliente confirmou o horário do treino para: ' . $session->proposed_datetime->format('H:i d/m/Y');

            
            event(new TrainingSessionTimeConfirmed($session, $msg));
            
            $user = Customer::find($session->customer_id)->user;

            $notification = new CreateNotificationService(
                     $user, 
                     'fit',
                     'Treino aceito!',
                      $msg 
                 );
            $notification->saveNotification();

            return $session;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}