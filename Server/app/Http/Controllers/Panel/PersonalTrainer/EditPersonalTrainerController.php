<?php

namespace App\Http\Controllers\Panel\PersonalTrainer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\State\GetStatesController;
use App\Services\Panel\PersonalTrainer\GetPersonalTrainersService;
use Illuminate\Support\Facades\Log;

class EditPersonalTrainerController extends Controller
{
    public function index($personalId)
    {
        try {
            $personalTrainer = (new GetPersonalTrainersService())->GetPersonalTrainer($personalId);
            $states = (new GetStatesController())->GetStates();
            return view('panel.pages.personalTrainer.edit', compact('personalTrainer', 'states'));
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir Personal: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir o Personal.']);
        }
    }
}
