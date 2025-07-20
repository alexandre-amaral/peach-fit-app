<?php

namespace App\Http\Controllers\Panel\PersonalTrainer;

use App\Http\Controllers\Controller;
use App\Services\Panel\PersonalTrainer\RegisterPersonalTrainerService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterPersonalTrainerController extends Controller
{
    public function index(Request $request)
    {
        try {
            DB::beginTransaction();
             $personal = (new RegisterPersonalTrainerService())->register($request);

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {

                new SaveAvatarUserService(userid: $personal->user_id, file: $request->file('avatar'));
            }
            DB::commit();

            return redirect()->route('admin.personal.add')->with(['success' => 'Personal cadastrado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao cadastrar usuário adminstrador: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
