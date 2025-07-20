<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\PersonalSchedule;
use App\Models\PersonalTrainer;

class DeletePersonalScheduleController extends Controller
{
    /**
     * Deleta horários na agenda do personal trainer
     * @return jsonResponse
     */

    public function delete(Request $request, $personalId)
    {
        $validated = $request->validate([
            'ids' => 'required|array'
        ]);

        try {
              $schedules = PersonalSchedule::whereIn('id', $validated['ids'])
                ->where('personal_id', $personalId)
                ->get();


                if ($schedules->count() !== count($validated['ids'])) {
                    return response()->json([
                        'error' => 'Alguns horários não pertencem a este personal.',
                    ], 403);
                }

                PersonalSchedule::whereIn('id', $validated['ids'])->delete();

                return response()->json([
                    'message' => 'Horários excluídos com sucesso!',
                ], 200);
            } catch (\Exception $e) {
                Log::error('Erro ao deletar horários da agenda: ' . $e->getMessage());

                return response()->json([
                    'error' => 'Erro ao deletar horários',
                    'details' => $e->getMessage(),
                ], 500);
        }
    }
       
}
