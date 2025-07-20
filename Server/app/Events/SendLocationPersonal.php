<?php

namespace App\Events;

use App\Models\TrainingSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendLocationPersonal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TrainingSession $session;
    public string $location;

    public function __construct(TrainingSession $session, string $location)
    {
        $this->session = $session;
        $this->location = $location;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('customer.' . $this->session->customer_id);
    }

    public function broadcastWith(): array
    {
        return [
            'location' => $this->location,
            'session_id' => $this->session->id,
        ];
    }

    public function broadcastAs(): string
    {
        return 'personal.location.sent';
    }
}
