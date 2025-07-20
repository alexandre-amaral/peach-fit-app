<?php

namespace App\Http\Controllers\App\Customers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Panel\Customers\GetCustomersService;
use App\Services\Panel\User\Avatar\GetAvatarUserService;

class GetCustomersAppController extends Controller
{

    
    /**
    * Busca todos os clientes
    * @return jsonResponse
    */

    public function index()
    {
        try {
            $customers = (new GetCustomersService())->getCustomers();

            return response()->json([
                'data' => $customers,
                'message' => 'Clientes listados com sucesso',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar clientes: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar clientes',
            ], 500);
        }
    }

    
    /**
     * Busca cliente por ID
     * @param int $id
     * @return jsonResponse
     */

    public function show($id)
    {
        try {
            $customer = (new GetCustomersService())->getCustomer($id);

            if (!$customer) {
                return response()->json([
                    'error' => 'Cliente não encontrado',
                ], 404);
            }
            
            $avatar = (new GetAvatarUserService())->getAvatar($customer->user_id);

            return response()->json([
                'data' => [
                    'customer' => $customer,
                    'avatar' => $avatar ? asset("storage/users/avatars/{$avatar}") : null,
                ],
                'message' => 'Cliente encontrado com sucesso',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar cliente: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar cliente',
            ], 500);
        }
    }
}
