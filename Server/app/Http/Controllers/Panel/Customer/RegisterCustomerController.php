<?php

namespace App\Http\Controllers\Panel\Customer;

use App\Http\Controllers\Controller;
use App\Services\Panel\Customers\RegisterCustomerService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterCustomerController extends Controller
{
    public function index(Request $request)
    {
        try {
            DB::beginTransaction();
            $customer = (new RegisterCustomerService()->register($request));

            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {

                new SaveAvatarUserService(userid: $customer->user_id, file: $request->file('avatar'));;
            }
            DB::commit();

            return redirect()->route('admin.customer.add')->with(['success' => 'Cliente cadastrado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao cadastrar usuário adminstrador: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
