<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\TrainingSession; 

//Evento de criação de uma interação cliente/personal, onde o personal nega o agendamento do cliente

class TrainingSessionDenied implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;
    public $message;

    
    public function __construct(TrainingSession $session, string $message = '')
    {
        $this->session = $session;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('customer.' . $this->session->customer_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'training.session.accepted';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->session->id,
            'status' => $this->session->status,
            'customer_id' => $this->session->customer_id,
            'personal_id' => $this->session->personal_id,
            'proposed_datetime' => $this->session->proposed_datetime?->toDateTimeString(),
            'confirmed_datetime' => $this->session->confirmed_datetime?->toDateTimeString(),
            'location' => $this->session->location,
            'payment_status' => $this->session->payment_status,
            'message' => $this->message,
        ];
    }
}