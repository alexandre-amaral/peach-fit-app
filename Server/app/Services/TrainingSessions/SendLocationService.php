<?php

namespace App\Services\TrainingSessions;

use App\Events\SendLocationCustomer;
use App\Events\SendLocationPersonal;
use App\Models\TrainingSession;
use App\Models\PersonalTrainer;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Notifications\CreateNotificationService;

class SendLocationService
{
    
   /**
    * Envia localização do cliente
    * 
    * @param int $sessionId
    * @param string $location
    */

    public function handleCustomerLocation(int $sessionId, string $location): void
    {
        $session = $this->findSession($sessionId);
        $session->customer_location = $location;
        $session->save();

        event(new SendLocationCustomer($session, $location));

        $user = Customer::find($session->customer_id)->user;
        
        $msg = 'Recebida a localização do cliente! '. $location;
        $notification = new CreateNotificationService(
            $user, 
            'fit',
            'Localização do cliente!',
            $msg 
        );

        $notification->saveNotification();

    }

  /**
    * Envia localização do personal
    * 
    * @param int $sessionId
    * @param string $location
    */
    public function handlePersonalLocation(int $sessionId, string $location): void
    {
        $session = $this->findSession($sessionId);
        $session->personal_location = $location;
        $session->save();

        event(new SendLocationPersonal($session, $location));

        $user = Customer::find($session->customer_id)->user;
        
        $msg = 'Recebida a localização do Personal! '. $location;
        $notification = new CreateNotificationService(
            $user, 
            'fit',
            'Localização do Personal!',
            $msg 
        );

        $notification->saveNotification();
        
    }

    protected function findSession(int $sessionId): TrainingSession
    {
        try {
            return TrainingSession::findOrFail($sessionId);
        } catch (ModelNotFoundException $e) {
        
            throw new \RuntimeException("Sessão de treino com ID {$sessionId} não encontrada.");
        }
    }
}
