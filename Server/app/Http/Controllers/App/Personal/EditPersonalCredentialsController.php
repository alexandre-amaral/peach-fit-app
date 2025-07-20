<?php

namespace App\Http\Controllers\App\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonalPaypalCredential;
use Illuminate\Validation\ValidationException;

class EditPersonalCredentialsController extends Controller
{
    /**
     * Atualiza credenciais do PayPal do personal trainer.
     * 
     * @param  Request  $request
     * @param  int  $personal_id
     * @return JsonResponse
     */
    public function update(Request $request, $personal_id)
    {
        try {
            $validatedData = $request->validate([
                'payment_email' => 'required|email|max:255',
            ]);

            $credential = PersonalPaypalCredential::where('personal_id', $personal_id)->first();

            if (!$credential) {
                return response()->json([
                    'success' => false,
                    'message' => 'Credencial não encontrada para o personal informado.'
                ], 404);
            }

            $credential->payment_email = $validatedData['payment_email'];
            $credential->save();

            return response()->json([
                'success' => true,
                'message' => 'Credencial atualizada com sucesso.',
                'data' => $credential
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar credencial.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
