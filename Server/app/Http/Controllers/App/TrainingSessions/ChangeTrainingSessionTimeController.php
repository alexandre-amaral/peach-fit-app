<?php

namespace App\Http\Controllers\App\TrainingSessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrainingSessions\ChangeTrainingSessionTimeService;

class ChangeTrainingSessionTimeController extends Controller
{
    protected $sessionService;

    public function __construct(ChangeTrainingSessionTimeService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
     * Personal altera o horário do treino.
     * 
     * @param Request $request
     * @param int $sessionId 
     * @return JsonResponse
     */
    public function changeTime(Request $request)
    {
        $request->validate([
            'session_id' => 'required|integer',
            'proposed_datetime' => 'required|date_format:Y-m-d H:i:s',
        ]);

        try {
            $session = $this->sessionService->changeTime(
                $request->input('session_id'),
                $request->input('proposed_datetime')
            );

            return response()->json([
                'message' => 'Horário do treino sugerido com sucesso!',
                'session' => $session
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

}
