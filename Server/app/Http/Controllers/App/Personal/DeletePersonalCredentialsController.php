<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use App\Models\PersonalPaypalCredential;
use Illuminate\Support\Facades\Log;

class DeletePersonalCredentialsController extends Controller
{
    /**
     * Deleta a credencial PayPal de um personal trainer.
     * @param int $personal_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($personal_id)
    {
        try {
            $credential = PersonalPaypalCredential::where('personal_id', $personal_id)->first();

            if (!$credential) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credencial não encontrada para este personal.'
                ], 404);
            }

            $credential->delete();

            return response()->json([
                'success' => true,
                'message' => 'Credencial PayPal deletada com sucesso.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao deletar credencial PayPal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar credencial.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
