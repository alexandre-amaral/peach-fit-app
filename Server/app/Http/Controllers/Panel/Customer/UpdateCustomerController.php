<?php

namespace App\Http\Controllers\Panel\Customer;

use App\Http\Controllers\Controller;
use App\Services\Panel\Customers\UpdateCustomerService;
use App\Services\Panel\User\Avatar\DeleteAvatarUserService;
use App\Services\Panel\User\Avatar\SaveAvatarUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateCustomerController extends Controller
{
    public function index(Request $request, $customerId)
    {
        try {
            DB::beginTransaction();
            $customer = (new UpdateCustomerService()->updateCustomer($request->all(), $customerId));
            if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
                new DeleteAvatarUserService($customer->user_id);
                new SaveAvatarUserService($customer->user_id, $request->file('avatar'));
            }

            DB::commit();
            return redirect()->route('admin.customer.edit', ['customerId' => $customerId])->with(['success' => 'Usuário atualizado com sucesso!']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::emergency('Falha ao atualizar Cliente: \n Linha: ' . $e->getLine() . ' \n Arquivo:' . $e->getFile() . ' \n Mensagem: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);

        }
    }
}
