<?php

namespace App\Http\Controllers\App\Customers;

use App\Http\Controllers\Controller;
use App\Services\Panel\Customers\RegisterCustomerService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use App\Http\Requests\CustomerRequest;
use App\Services\Panel\User\Avatar\ConvertFileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterCustomerAppController extends Controller
{
     /**
     * Cadastro de clientes
     * 
     * @param CustomerRequest $request
     * @return jsonResponse
     */
    
    public function registerCustomer(CustomerRequest $request)
    {       
        try {
            DB::beginTransaction();

            $customer = (new RegisterCustomerService())->register($request);

            if ($base64 = $request->input('avatar')) {
                // Converte base64 para UploadedFile
                 $avatarFile = (new ConvertFileService())->convertBase64ToFile($base64);

                new SaveAvatarUserService(
                    userid: $customer->user_id,
                    file: $avatarFile
                );
            }
            DB::commit();

            return response()->json([
                'message' => 'Cliente cadastrado com sucesso!',
                'customer' => $customer
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao cadastrar cliente: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'error' => 'Falha ao cadastrar cliente',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
