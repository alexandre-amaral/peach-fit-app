<?php

namespace App\Http\Controllers\Panel\User;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Services\Panel\User\RegisterUserService;

class RegisterUserAdminController extends Controller
{
    public function index(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_id = (new RegisterUserService())->registerUser($request, 'admin');

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {

                new SaveAvatarUserService(userid: $user_id, file: $request->file('avatar'));;
            }
            DB::commit();

            return redirect()->route('admin.view.add')->with(['success' => 'Usuário cadastrado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao cadastrar usuário adminstrador: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()])->withInput(['name', 'email']);
        }
    }
}
