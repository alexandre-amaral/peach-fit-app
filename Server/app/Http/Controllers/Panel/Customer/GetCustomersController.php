<?php

namespace App\Http\Controllers\Panel\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\State\GetStatesController;
use App\Services\Panel\Customers\GetCustomersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GetCustomersController extends Controller
{
    public function index()
    {
        try {
            $customers = (new GetCustomersService())->getCustomers();
            return view("panel.pages.customer.index", compact("customers"));
    
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir Personais Trainers: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os Clientes.']);
        }
    }

    public function addCustomerView()
    { 
        $states = (new GetStatesController())->getStates();
        return view('panel.pages.customer.add', compact('states'));
    }
}
