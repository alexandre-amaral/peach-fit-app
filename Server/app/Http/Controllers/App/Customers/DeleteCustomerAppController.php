<?php

namespace App\Http\Controllers\App\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Panel\Customers\DeleteCustomerService;

class DeleteCustomerAppController extends Controller
{

    /**
     * Deleta cliente por ID
     * 
     * @param Request $request
     * @param int $id 
     * @return jsonResponse
     */

    public function deleteCustomer(Request $request, $id) {
       try {
           DB::beginTransaction();
            $customer = (new DeleteCustomerService())->delete($id);
            DB::commit();
            
            return response()->json([
                'message' => 'Cliente deletado com sucesso!',
            ], 200);
          

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao deletar Cliente: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'error' => 'Falha ao deletar Cliente',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}