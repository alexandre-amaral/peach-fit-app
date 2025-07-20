<?php

namespace App\Http\Controllers\App\Reviews;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PersonalSchedule;
use App\Services\Reviews\StoreReviewService;

class StoreReviewController extends Controller
{
    /**
     * Armazena reviews do personal e do cliente
     * @return jsonResponse
     */

   public function store(Request $request)
   {
        
        $validated = $request->validate([
            'training_session_id' => 'nullable|exists:training_sessions,id',
            'reviewed_by' => 'required|in:customer,personal',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        
        try {
            $review =  (new StoreReviewService())->storeReview($request->all());

            return response()->json([
                'message' => 'Review registrada com sucesso!',
                'review' => $review
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'error' => 'Erro ao registrar review',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
