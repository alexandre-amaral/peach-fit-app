<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Panel\PersonalTrainer\DeletePersonalTrainerService;
use App\Services\Panel\User\Avatar\GetAvatarUserService;
use App\Models\PersonalSchedule;
use Illuminate\Support\Facades\DB;
use App\Models\PersonalPaypalCredential;

class DeletePersonalAppController extends Controller
{
   /**
     * Deleta personal trainer por ID
     * 
     * @param Request $request
     * @param int $id 
     * @return jsonResponse
     */

    public function deletePersonal(Request $request, $id) {
       try {
            DB::beginTransaction();

            PersonalPaypalCredential::where('personal_id', $id)->delete();

            PersonalSchedule::where('personal_id', $id)->delete();

            $personal = (new DeletePersonalTrainerService())->delete($id);

            DB::commit();
            
            return response()->json([
                'message' => 'Personal Trainer deletado com sucesso!',
            ], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao deletar Personal Trainer: Linha: ' . $e->getLine() . ' Arquivo:' . $e->getFile() . ' Mensagem: ' . $e->getMessage());

            return response()->json([
                'error' => 'Falha ao deletar Personal Trainer',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}