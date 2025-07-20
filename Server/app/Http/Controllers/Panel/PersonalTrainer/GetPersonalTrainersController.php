<?php

namespace App\Http\Controllers\Panel\PersonalTrainer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\State\GetStatesController;
use App\Services\Panel\PersonalTrainer\GetPersonalTrainersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetPersonalTrainersController extends Controller
{
    public function index(Request $request)
    {
        try {
            $personalTrainers = (new GetPersonalTrainersService())->GetPersonalTrainers();
            return view('panel.pages.personalTrainer.index', compact('personalTrainers'));
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir Personais Trainers: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os Personais.']);
        }
    }

    public function addPersonalView()
    {
        $states = (new GetStatesController())->getStates();
        return view('panel.pages.personalTrainer.add', compact('states'));
    }
}
