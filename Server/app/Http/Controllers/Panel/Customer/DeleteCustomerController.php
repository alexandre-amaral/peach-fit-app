<?php

namespace App\Http\Controllers\Panel\Customer;

use App\Http\Controllers\Controller;
use App\Services\Panel\Customers\DeleteCustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeleteCustomerController extends Controller
{
    public function index($customerId)
    {
        try {
            (new DeleteCustomerService())->delete($customerId);
            return redirect()->route("admin.view.customer")->with("success","Cliente deletado com Sucesso!");
        } catch (\Exception $e) {
            Log::emergency('Falha ao excluir cliente: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Falha ao exibir os personal.']);
        }
    }
}
