<?php

namespace App\Http\Controllers\Panel\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Panel\User\UpdateUserService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use App\Services\Panel\User\Avatar\DeleteAvatarUserService;

class UpdateUserController extends Controller
{
    public function index(Request $request, $userid)
    {
        try {
            DB::beginTransaction();
            (new UpdateUserService())->updateUser($request, $userid);

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                new DeleteAvatarUserService($userid);
                new SaveAvatarUserService(userid: $userid, file: $request->file('avatar'));;
            }
            DB::commit();

            return redirect()->route('admin.view.edit', ['userid' => $userid])->with(['success' => 'Usuário atualizado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao atualizar usuário adminstrador: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()])->withInput(['name', 'email']);
        }
    }
}
