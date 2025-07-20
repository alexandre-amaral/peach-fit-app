<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PayoutProcessedSuccess implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payout;
    public $trainingSessionIds;
    public $personalId;


    public function __construct($payout, int $personalId, array $trainingSessionIds)
    {
        $this->payout = $payout;
        $this->personalId = $personalId;
        $this->trainingSessionIds = $trainingSessionIds;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('personal.' . $this->personalId);
    }

    public function broadcastWith(): array
    {
        return [
            'payout_id' => $this->payout->id ?? null,
            'paypal_batch_id' => $this->payout->paypal_batch_id ?? null,
            'payout_status' => $this->payout->status ?? null,
            'total_amount' => $this->payout->total_amount ?? null,
            'currency' => $this->payout->currency ?? null,
            'training_session_ids' => $this->trainingSessionIds,
            'message' => 'Um pagamento foi processado para você!',
        ];
    }

    public function broadcastAs(): string
    {
        return 'payout.processed.personal';
    }
}
