<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalSchedule;
use Illuminate\Support\Facades\Log;

class GetPersonalScheduleController extends Controller
{
    /**
     * Retorna os horários da agenda de um personal trainer
     *
     * @param int $personal_id
     * @return JsonResponse
     */
    public function show($personal_id)
    {
        try {
            $schedules = PersonalSchedule::where('personal_id', $personal_id)->get();

            if ($schedules->isEmpty()) {
                return response()->json([
                    'message' => 'Nenhum horário encontrado para este personal.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'message' => 'Horários encontrados com sucesso.',
                'data' => $schedules
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar horários do personal: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar horários.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
