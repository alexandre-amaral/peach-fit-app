<?php

namespace App\Events;

use App\Models\Review;
use App\Models\TrainingSession;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewReview implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;
    public $review;

    public function __construct(TrainingSession $session, Review $review)
    {
        $this->session = $session;
        $this->review = $review;
    }

    public function broadcastOn(): array
    {
        if ($this->review->reviewed_by === 'personal') {
            return [
                new PrivateChannel('personal.' . $this->session->personal_id)
            ];
        }

        return [
            new PrivateChannel('customer.' . $this->session->customer_id)
        ];
    }

    public function broadcastAs(): string
    {
        return 'new.review';
    }

    public function broadcastWith(): array
    {
        return [
            'training_session_id' => $this->session->id,
            'review_id' => $this->review->id,
            'rating' => $this->review->rating,
            'reviewed_by' => $this->review->reviewed_by,
            'message' => 'Nova avaliação recebida!'
        ];
    }
}
