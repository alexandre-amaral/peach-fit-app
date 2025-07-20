<?php

namespace app\Http\Controllers\App\Personal;

use App\Models\PersonalPaypalCredential;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StorePersonalCredentialsController extends Controller
{
     /**
     * Armazena credenciais do paypal do personal trainer
     * @return jsonResponse
     */
    
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'personal_id' => 'required|exists:personal_trainers,id|unique:personal_paypal_credentials,personal_id',
                'payment_email' => 'required|email|max:255',
            ]);

            $credential = PersonalPaypalCredential::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Credencial PayPal salva com sucesso.',
                'data' => $credential
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
