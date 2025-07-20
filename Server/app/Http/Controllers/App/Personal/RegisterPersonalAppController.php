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

            $personal = (new RegisterPersonalTrainerService())->register($request);

             if ($base64 = $request->input('avatar')) {
                // Converte base64 para UploadedFile
                 $avatarFile = (new ConvertFileService())->convertBase64ToFile($base64);

                new SaveAvatarUserService(
                    userid: $personal->user_id,
                    file: $avatarFile
                );
            }
            DB::commit();

            return response()->json([
                'message' => 'Personal Trainer cadastrado com sucesso!',
                'personal' => $personal
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao cadastrar Personal Trainer: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'error' => 'Falha ao cadastrar Personal Trainer',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
