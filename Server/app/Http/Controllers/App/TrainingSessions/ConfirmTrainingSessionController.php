<?php

namespace App\Http\Controllers\App\TrainingSessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrainingSessions\ConfirmSessionInviteService;
use Illuminate\Support\Facades\Auth; 

class ConfirmTrainingSessionController extends Controller
{
    protected $sessionService;

    public function __construct(ConfirmSessionInviteService $sessionService)
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

    public function confirm(Request $request, int $sessionId)
    {
        try {
            $session = $this->sessionService->confirmSessionTime($sessionId);

            return response()->json([
                'message' => 'Treino confirmado com sucesso!',
                'session' => $session
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}