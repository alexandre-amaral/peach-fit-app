<?php

    namespace App\Services\TrainingSessions;

    use App\Models\TrainingSession;
    use App\Models\Customer; 
    use App\Models\PersonalTrainer; 
    use App\Events\TrainingSessionAccepted;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use App\Services\Notifications\CreateNotificationService;

class AcceptSessionInviteService
{
    /**
     * Personal aceita o convite do treino.
     *
     * @param int 
     * @return TrainingSession
     */

    public function acceptSession(int $sessionId)
    {
        DB::beginTransaction();
        try {
            $session = TrainingSession::find($sessionId);

            if (!$session) {
                throw new \Exception("Sessão de treino não encontrada.");
            }

            $user = Auth::user();
            $personal = PersonalTrainer::where('user_id', $user->id)->first();

            if (!$personal || $session->personal_id !== $personal->id) {
                throw new \Exception("Você não tem permissão para aceitar este treino");
            }

            $session->update(['status' => 'accepted_by_personal']);

            DB::commit();

             
            $user = Customer::find($session->customer_id)->user;


            $msg = 'Seu treino foi aceito pelo Personal Trainer! Confirme o horário:' .  $session->proposed_datetime->format('H:i d/m/Y'); 
           
            event(new TrainingSessionAccepted($session, $msg));

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
            Log::error("Erro ao aceitar sessão: " . $e->getMessage());
            throw $e;
        }
    }
}