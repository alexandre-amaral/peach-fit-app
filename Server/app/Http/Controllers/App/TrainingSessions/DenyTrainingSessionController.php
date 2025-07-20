<?php

namespace App\Http\Controllers\App\TrainingSessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrainingSessions\DenyTrainingSessionService;
use Illuminate\Support\Facades\Auth; 

class DenyTrainingSessionController extends Controller
{
    protected $sessionService;

    public function __construct(DenyTrainingSessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
    * Confirma o horário do treino
    * 
    * @param Request $request
    * @param int $sessionId 
    * @return jsonResponse
    */

    public function deny(Request $request, int $sessionId)
    {
        try {
            $session = $this->sessionService->denySession($sessionId);

            return response()->json([
                'message' => 'Treino negado com sucesso!',
                'session' => $session
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}