<?php

namespace App\Http\Controllers\App\TrainingSessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrainingSessions\AcceptSessionInviteService;
use Illuminate\Support\Facades\Auth; 

class AcceptTrainingSessionController extends Controller
{
    protected $sessionService;

    public function __construct(AcceptSessionInviteService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
    * Aceita a proposta de treino do cliente
    * 
    * @param Request $request
    * @param int $sessionId 
    * @return jsonResponse
    */

   public function accept(Request $request, int $sessionId)
    {
        try {
            $session = $this->sessionService->acceptSession($sessionId);
            return response()->json([
                'message' => 'Treino aceito com sucesso!',
                'session' => $session
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}