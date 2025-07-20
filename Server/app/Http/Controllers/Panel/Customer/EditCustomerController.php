<?php

namespace App\Http\Controllers\Panel\Customer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\General\State\GetStatesController;
use App\Services\Panel\Customers\GetCustomersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EditCustomerController extends Controller
{
    public function index(Request $request, $customerId)
    {
        try {
            $customer = (new GetCustomersService())->getCustomer($customerId);
            $states = (new GetStatesController())->GetStates();
            return view('panel.pages.customer.edit', compact('customer', 'states'));
        } catch (\Exception $e) {
            Log::emergency('Falha ao exibir Cliente: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir o Cliente.']);
        }
    }
}
