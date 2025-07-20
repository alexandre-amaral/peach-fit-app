<?php

namespace App\Http\Controllers\App\TrainingSessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\TrainingSessions\GetTrainingSessionsService;

class GetTrainingSessionsController extends Controller
{

    /**
    * Busca todos os treinos agendados
    * @return jsonResponse
    */

    public function index()
    {
        try {
            $trainingSessions = (new GetTrainingSessionsService())->getTrainingSessions();

            return response()->json([
                'data' => $trainingSessions,
                'message' => 'Treinos listadas com sucesso',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar Treinos: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar Treinos',
            ], 500);
        }
    }

    
    /**
     * Busca Treinos por ID do personal
     * @param int $id
     * @return jsonResponse
     */

    public function getTrainingSessionsByPersonal($id)
    {
        try {
            $trainingSessions = (new GetTrainingSessionsService())->getTrainingSessionsByPersonal($id);

            if (!$trainingSessions) {
                return response()->json([
                    'error' => 'Treinos não encontrados para esse personal',
                ], 404);
            }

            return response()->json([
                'data' => $trainingSessions,
                'message' => 'Treinos listadas com sucesso',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar Treinos: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar Treinos',
            ], 500);
        }
    }


      /**
     * Busca Treinos por ID do cliente
     * @param int $id
     * @return jsonResponse
     */

    public function getTrainingSessionsByCustomer($id)
    {
        try {
            $trainingSessions = (new GetTrainingSessionsService())->getTrainingSessionsByCustomer($id);

            if (!$trainingSessions) {
                return response()->json([
                    'error' => 'Treinos não encontrados para esse cliente',
                ], 404);
            }

            
            return response()->json([
                'data' => $trainingSessions,
                'message' => 'Treinos listadas com sucesso',
            ], 200);

        } catch (\Exception $e) {
            Log::error('EErro ao buscar Treinos: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar Treinos',
            ], 500);
        }
    }
}
