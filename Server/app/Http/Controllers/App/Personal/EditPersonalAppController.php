<?php

namespace App\Http\Controllers\App\Personal;


use App\Http\Controllers\Controller;
use App\Services\Panel\PersonalTrainer\UpdatePersonalTrainnerService;
use App\Services\Panel\User\Avatar\DeleteAvatarUserService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use App\Services\Panel\User\Avatar\ConvertFileService;
use App\Http\Requests\PersonalTrainerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditPersonalAppController extends Controller
{
    /**
     * Edita personal trainer por ID
     * 
     * @param Request $request
     * @param int $id 
     * @return jsonResponse
     */

    public function editPersonal(PersonalTrainerRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $personal = (new UpdatePersonalTrainnerService())->updatePersonalTrainer($request->all(), $id);

             if ($base64 = $request->input('avatar')) {
                // Converte base64 para UploadedFile
                 $avatarFile = (new ConvertFileService())->convertBase64ToFile($base64);

                new DeleteAvatarUserService($personal->user_id);

                new SaveAvatarUserService(
                    userid: $personal->user_id,
                    file: $avatarFile
                );
            }
            DB::commit();

            return response()->json([
                'message' => 'Personal Trainer atualizado com sucesso!',
                'personal' => $personal
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao atualizar Personal Trainer: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'error' => 'Falha ao atualizar Personal Trainer',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}

