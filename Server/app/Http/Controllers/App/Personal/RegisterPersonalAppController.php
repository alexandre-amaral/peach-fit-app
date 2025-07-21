<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use App\Services\Panel\PersonalTrainer\RegisterPersonalTrainerService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;

use App\Services\Panel\User\Avatar\ConvertFileService;
use App\Http\Requests\PersonalTrainerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterPersonalAppController extends Controller
{
    
    /**
    * Cadastro de personal trainer
    * 
    * @param PersonalTrainerRequest $request
    * @return jsonResponse
    */
    
    public function registerPersonal(PersonalTrainerRequest $request)
    {       
        try {
            DB::beginTransaction();

            // ✅ Usar método específico para API
            $response = (new RegisterPersonalTrainerService())->registerForApi($request);

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
            Log::emergency('Falha ao cadastrar Personal Trainer: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Falha ao cadastrar Personal Trainer',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
