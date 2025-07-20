<?php

namespace App\Http\Controllers\Panel\PersonalTrainer;

use App\Http\Controllers\Controller;
use App\Services\Panel\PersonalTrainer\DeletePersonalTrainerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeletePersonalTrainerController extends Controller
{
    public function index($personalId)
    {
        try {
            (new DeletePersonalTrainerService())->delete($personalId);
            return redirect()->route("admin.view.personalTrainers")->with("success","Personal deletado com Sucesso!");
        } catch (\Exception $e) {
            Log::emergency('Falha ao excluir personal: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os personal.']);
        }
    }
}
