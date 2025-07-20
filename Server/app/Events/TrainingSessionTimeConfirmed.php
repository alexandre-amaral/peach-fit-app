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
use Carbon\Carbon; 

//Evento de criação de uma interação cliente/personal, onde o cliente confirma o horário de agendamento

class TrainingSessionTimeConfirmed implements ShouldBroadcast
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
            new PrivateChannel('personal.' . $this->session->personal_id)
        ];
    }

    public function broadcastAs(): string
    {
        return 'training.session.time_confirmed';
    }

    public function broadcastWith(): array
    {
        return [
            'session_id' => $this->session->id,
            'status' => $this->session->status, 
            'customer_id' => $this->session->customer_id,
            'personal_id' => $this->session->personal_id,
            'proposed_datetime' => $this->session->proposed_datetime?->format('H:i d/m/Y'),
            'confirmed_datetime' => $this->session->confirmed_datetime?->format('H:i d/m/Y'), 
            'location' => $this->session->location,
            'payment_status' => $this->session->payment_status,
            'message' => $this->message,
        ];
    }
}