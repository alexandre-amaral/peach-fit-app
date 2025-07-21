<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\PersonalTrainer;

class PersonalTrainerRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }


    public function rules(): array
    {
        $rules = [
            'name'         => 'required|string|max:255',
            'avatar'       => 'nullable|string',
            'tel'          => 'required|string|max:20',
            'speciality'   => 'required|string|max:255',
            'gender'       => 'required|in:male,female,other',
            'rate'         => 'required|numeric|min:0',
            'password'     => 'required|string|min:6', // ✅ Validação de senha
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            
            $personal = PersonalTrainer::find($id);
            $userId = $personal ? $personal->user_id : null;

            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId, 'id')
            ];
            

            $rules['cpf'] = [
                'required',
                'string',
                'size:11',
                Rule::unique('personal_trainers', 'cpf')->ignore($id, 'id')
            ];
            
            // ✅ Senha opcional na atualização
            $rules['password'] = 'nullable|string|min:6';
            
        } else {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['cpf']   = 'required|string|size:11|unique:personal_trainers,cpf';
        }

        return $rules;
    }

        
        protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Erro de validação',
            'errors' => $validator->errors()
        ], 422));
    }

}
