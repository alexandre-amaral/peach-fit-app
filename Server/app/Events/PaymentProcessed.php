<?php

namespace App\Events;

use App\Models\Payment; 
use App\Models\TrainingSession; 
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; 

class PaymentProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $session;

    public function __construct(Payment $payment, TrainingSession $trainingSession)
    {
        $this->payment = $payment;
        $this->session = $trainingSession;
    }

    
    public function broadcastOn()
    {
        return [
            new PrivateChannel('personal.' . $this->session->personal_id),
        ];
    }

    
    public function broadcastWith(): array
    {
      return [
            'training_session_id' => $this->session->id,
            'payment_status' => $this->session->payment_status, 
            'amount' => $this->payment->amount,
            'currency' => $this->payment->currency,
            'status' => $this->payment->status, 
            'message' => 'Pagamento da sessão de treino realizado com sucesso.',
        ];
    }

    public function broadcastAs(): string
    {
        return 'payment.processed';
    }

}