<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'birthdate' => 'required|date|before_or_equal:today',
            "bio" => 'required|string|min:3'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma sequência de caracteres.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',

            'birthdate.required' => 'O campo data de nascimento é obrigatório.',
            'birthdate.date' => 'A data de nascimento deve estar em um formato válido (AAAA-MM-DD).',
            'birthdate.before_or_equal' => 'A data de nascimento não pode ser no futuro.',

            'bio.required' => 'O campo biografia é obrigatório.',
            'bio.string' => 'A biografia deve ser uma sequência de caracteres.',
            'bio.min' => 'A biografia deve ter no mínimo 3 caracteres.'
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
