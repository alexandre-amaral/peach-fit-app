<?php

namespace App\Http\Controllers\App\Customers;


use App\Http\Controllers\Controller;
use App\Services\Panel\Customers\UpdateCustomerService;
use App\Services\Panel\User\Avatar\DeleteAvatarUserService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use App\Services\Panel\User\Avatar\ConvertFileService;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditCustomerAppController extends Controller
{
    /**
     * Edita um cliente por ID
     * 
     * @param CustomerRequest $request
     * @param int $id 
     * @return jsonResponse
     */
    public function editCustomer(CustomerRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $customer = (new UpdateCustomerService())->updateCustomer($request->all(), $id);

             if ($base64 = $request->input('avatar')) {
                // Converte base64 para UploadedFile
                 $avatarFile = (new ConvertFileService())->convertBase64ToFile($base64);

                new DeleteAvatarUserService($customer->user_id);

                new SaveAvatarUserService(
                    userid: $customer->user_id,
                    file: $avatarFile
                );
            }
            DB::commit();

            return response()->json([
                'message' => 'Cliente atualizado com sucesso!',
                'customer' => $customer
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao atualizar Cliente: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'error' => 'Falha ao atualizar Cliente',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}

