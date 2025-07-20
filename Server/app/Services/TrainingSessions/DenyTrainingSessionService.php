<?php

    namespace App\Services\TrainingSessions;

    use App\Models\TrainingSession;
    use App\Models\Customer; 
    use App\Models\PersonalTrainer; 
    use App\Events\TrainingSessionDenied;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use App\Services\Notifications\CreateNotificationService;

class DenyTrainingSessionService
{
    /**
     * Personal nega o convite do treino.
     *
     * @param int 
     * @return TrainingSession
     */

    public function denySession(int $sessionId)
    {
        DB::beginTransaction();
        try {
            $session = TrainingSession::find($sessionId);

            if (!$session) {
                throw new \Exception("Sessão de treino não encontrada.");
            }

            $user = Auth::user();
            $personal = PersonalTrainer::where('user_id', $user->id)->first();


            $session->update(['status' => 'denied']);

            DB::commit();

            $msg = 'Seu treino foi negado pelo Personal Trainer'; 
            
            $user = Customer::find($session->customer_id)->user;

            event(new TrainingSessionDenied($session, $msg));

            $notification = new CreateNotificationService(
                     $user, 
                     'fit',
                     'Treino Negado',
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