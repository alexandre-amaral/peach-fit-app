<?php

namespace App\Http\Controllers\App\Reviews;

use App\Services\Reviews\CalculateReviewsService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class GetReviewController extends Controller
{
    protected CalculateReviewsService $calculateReviewsService;

    public function __construct(CalculateReviewsService $calculateReviewsService)
    {
        $this->calculateReviewsService = $calculateReviewsService;
    }

    public function index()
    {
        $reviews = Review::all();

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

      /**
     * Retorna mediana das notas recebidas pelo personal trainer
     */
    public function getByPersonal(int $personalId)
    {
        $reviews = Review::where('personal_id', $personalId)
        ->where('reviewed_by', 'customer')->get();

        $median = $reviews->isNotEmpty() 
            ? $this->calculateReviewsService->getMedian($reviews->pluck('rating'))
            : null;

        return response()->json([
            'median_rate' => $median,
        ]);
    }

    /**
     * Retorna mediana das notas recebidas pelo cliente
     */
    public function getByCustomer(int $customerId)
    {
        $reviews = Review::where('customer_id', $customerId)
            ->where('reviewed_by', 'personal')->get();

        $median = $reviews->isNotEmpty()
            ? $this->calculateReviewsService->getMedian($reviews->pluck('rating'))
            : null;

        return response()->json([
            'median_rate' => $median,
        ]);
    }
}
