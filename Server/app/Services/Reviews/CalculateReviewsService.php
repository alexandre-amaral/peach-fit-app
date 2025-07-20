<?php

namespace App\Services\Reviews;

use Illuminate\Support\Collection;

class CalculateReviewsService
{
    /**
     * Calcula a mediana das notas de review
     *
     * @param Collection|array $rates
     */
    
   public function getMedian($rates)
    {
        if (is_array($rates)) {
            $rates = collect($rates);
        }

        $sorted = $rates->sort()->values();
        $count = $sorted->count();

        if ($count === 0) {
            return null;
        }

        if ($count % 2 === 0) {
            $midUpper = $count / 2;
            $midLower = $midUpper - 1;
            return ($sorted->get($midLower) + $sorted->get($midUpper)) / 2;
        }

        return $sorted->get(floor($count / 2));
    }

}
