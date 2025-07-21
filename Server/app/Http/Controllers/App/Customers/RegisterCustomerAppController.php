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

            // ✅ Usar método específico para API
            $response = (new RegisterCustomerService())->registerForApi($request);

            if ($base64 = $request->input('avatar')) {
                // Converte base64 para UploadedFile
                 $avatarFile = (new ConvertFileService())->convertBase64ToFile($base64);

                new SaveAvatarUserService(
                    userid: $response['data']['id'], // ✅ Usar ID do response
                    file: $avatarFile
                );
            }
            DB::commit();

            // ✅ Retornar resposta no formato esperado
            return response()->json($response, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao cadastrar cliente: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Falha ao cadastrar cliente',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
