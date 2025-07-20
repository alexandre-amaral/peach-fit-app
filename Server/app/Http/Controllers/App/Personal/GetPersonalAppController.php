<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\Panel\PersonalTrainer\GetPersonalTrainersService;
use App\Services\Panel\User\Avatar\GetAvatarUserService;

class GetPersonalAppController extends Controller
{

    /**
     * Busca todos personal trainers
     * @return jsonResponse
     */

    public function index()
    {
        try {
            $personals = (new GetPersonalTrainersService())->GetPersonalTrainers();

            return response()->json([
                'data' => $personals,
                'message' => 'Personal trainers listados com sucesso',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar personal trainer: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar personal trainers',
            ], 500);
        }
    }

      
    /**
    * Busca personal trainer por ID
    * @param int $id
    * @return jsonResponse
    */

    public function show($id)
    {
        try {
            $personal = (new GetPersonalTrainersService())->GetPersonalTrainer($id);

            if (!$personal) {
                return response()->json([
                    'error' => 'Personal Trainer não encontrado',
                ], 404);
            }
            $avatar = (new GetAvatarUserService())->getAvatar($personal->user_id);

            return response()->json([
                'data' => [
                    'customer' => $personal,
                    'avatar' => $avatar ? asset("storage/users/avatars/{$avatar}") : null,
                ],
                'message' => 'Personal Trainer encontrado com sucesso',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Erro ao buscar personal: ' . $e->getMessage());

            return response()->json([
                'error' => 'Erro ao buscar personal',
            ], 500);
        }
    }
}
