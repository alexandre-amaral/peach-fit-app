<?php

namespace App\Http\Controllers\App\TrainingSessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrainingSessions\SendSessionInviteService;
use Illuminate\Support\Facades\Auth; 

class SendSessionInviteController extends Controller
{
    protected $sessionService;

    public function __construct(SendSessionInviteService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
    * Cliente solicita um treino para o personal
    * 
    * @param Request $request
    * @param int $sessionId 
    * @return jsonResponse
    */
    public function invite(Request $request)
    {
        $request->validate([
            'personal_id' => 'required|exists:personal_trainers,id',
            'proposed_datetime' => 'required|date_format:d-m-Y H:i:s|after_or_equal:now',
            'duration' => 'required|numeric'
        ]);

        try {
            $session = $this->sessionService->inviteSession(
                $request->personal_id,
                $request->proposed_datetime,
                $request->duration
            );

            return response()->json([
                'message' => 'Solicitação de treino enviada com sucesso!',
                'session' => $session
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}