<?php

namespace App\Http\Controllers\Panel\PersonalTrainer;

use App\Http\Controllers\Controller;
use App\Services\Panel\PersonalTrainer\UpdatePersonalTrainnerService;
use App\Services\Panel\User\Avatar\DeleteAvatarUserService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdatePersonalTrainnerController extends Controller
{
    public function index(Request $request, $personalTrainerId)
    {
        try {
            DB::beginTransaction();
            $personal = (new UpdatePersonalTrainnerService()->updatePersonalTrainer($request->all(), $personalTrainerId));
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                new DeleteAvatarUserService($personal->user_id);
                new SaveAvatarUserService($personal->user_id, $request->file('avatar'));
            }

            DB::commit();
            return redirect()->route('admin.personal.edit', ['personalId' => $personalTrainerId])->with(['success' => 'Usuário atualizado com sucesso!']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao atualizar Personal: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);

        }
    }
}
