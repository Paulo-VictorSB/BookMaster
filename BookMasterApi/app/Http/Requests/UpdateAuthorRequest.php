<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'name' => 'string|min:3|max:255',
            'birthdate' => 'date|before_or_equal:today',
            'bio' => 'string|min:3|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O campo ID é obrigatório.',
            'id.integer' => 'O ID deve ser um número inteiro.',

            'name.string' => 'O nome deve ser uma sequência de caracteres.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',

            'birthdate.date' => 'A data de nascimento deve estar em um formato válido (AAAA-MM-DD).',
            'birthdate.before_or_equal' => 'A data de nascimento não pode ser uma data futura.',
            
            'bio.string' => 'A biografia deve ser uma sequência de caracteres.',
            'bio.min' => 'A biografia deve ter no mínimo 3 caracteres.',
            'bio.max' => 'A biografia não pode ultrapassar 255 caracteres.',
        ];
    }


    public function failedValidation(Validator $validator)
    {
        $response = new Response();

        throw new HttpResponseException(
            $response
                ->setStatus('error')
                ->setCode(422)
                ->setErrorMessage('Erro de validação dos campos.')
                ->setAditionalFields($validator->errors(), 'errors')
                ->response()
        );
    }
}
