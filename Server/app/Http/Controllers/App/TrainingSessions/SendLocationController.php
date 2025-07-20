<?php

namespace App\Http\Controllers\App\TrainingSessions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TrainingSessions\SendLocationService;

class SendLocationController extends Controller
{
    protected $locationService;

    public function __construct(SendLocationService $locationService)
    {
        $this->locationService = $locationService;
    }

   /**
    * Envia localização do cliente
    * 
    * @param Request $request
    * @return jsonResponse
    */

   public function fromCustomer(Request $request)
    {
        $data = $request->validate([
            'session_id' => 'required|integer|exists:training_sessions,id',
            'location' => 'required|string|max:255',
        ]);

        $this->locationService->handleCustomerLocation($data['session_id'], $data['location']);

        return response()->json(['message' => 'Localização do cliente enviada com sucesso.']);
    }

    
   /**
    * Envia localização do personal
    * 
    * @param Request $request
    * @return jsonResponse
    */
    public function fromPersonal(Request $request)
    {
        $data = $request->validate([
            'session_id' => 'required|integer|exists:training_sessions,id',
            'location' => 'required|string|max:255',
        ]);

        $this->locationService->handlePersonalLocation($data['session_id'], $data['location']);

        return response()->json(['message' => 'Localização do personal enviada com sucesso.']);
    }

}
