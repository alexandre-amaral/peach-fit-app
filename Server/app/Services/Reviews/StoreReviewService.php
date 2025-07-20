<?php

namespace App\Services\Reviews;

use App\Models\TrainingSession;
use App\Services\Notifications\CreateNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Review;
use App\Models\Customer;
use App\Events\NewReview;
use App\Models\PersonalTrainer;

class StoreReviewService
{
    /**
     * Manda nota do cliente/personal
     */
    public function storeReview(array $request)
    {
        $training_session = TrainingSession::find($request['training_session_id']);
        $review = Review::create([
            'customer_id' => $training_session->customer_id,
            'personal_id' => $training_session->personal_id, 
            'training_session_id' => $request['training_session_id'],
            'reviewed_by' => $request['reviewed_by'],
            'rating' => $request['rating'],
        ]);


        event(new NewReview($training_session, $review));
        
        if ($request['reviewed_by'] === 'customer') {
            $user = Customer::find($training_session->customer_id)->user;
        } elseif ($request['reviewed_by'] === 'personal') {
            $user = PersonalTrainer::find($training_session->personal_id)->user;
        } else {
            $user = null;
        }

        $msg = 'Você recebeu uma avaliação de ' . $request['rating'] . ' estrelas!'; 

        if ($user) {
            $notification = new CreateNotificationService(
                $user,
                'star',
                'Você recebeu uma avaliação!',
                $msg
            );
        }
        
        $notification->saveNotification();


        return $review;
    }

}