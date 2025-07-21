<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Customer;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'state'    => 'required|numeric',
            'city'     => 'required|numeric',
            'avatar'   => 'nullable',
            'password' => 'required|string|min:6', // ✅ Validação de senha
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $id = $this->route('id');
            $customer = Customer::find($id);
            $userId = $customer ? $customer->user_id : null;

            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId, 'id')
            ];

            $rules['cpf'] = [
                'required',
                'string',
                Rule::unique('customers', 'cpf')->ignore($id, 'id')
            ];

            $rules['tel'] = [
                'required',
                'string',
                Rule::unique('customers', 'tel')->ignore($id, 'id')
            ];
            
            // ✅ Senha opcional na atualização
            $rules['password'] = 'nullable|string|min:6';
            
        } else {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['cpf']   = 'required|string|unique:customers,cpf';
            $rules['tel']   = 'required|string|unique:customers,tel';
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
