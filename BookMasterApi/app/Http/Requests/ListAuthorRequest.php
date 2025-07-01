<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListAuthorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'birthdate' => 'digits:4|integer|min:1000|max:' . date('Y'),
            'bio' => 'string|min:3'
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser uma sequência de caracteres.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',

            'birthdate.digits' => 'O ano deve conter exatamente 4 dígitos.',
            'birthdate.integer' => 'O ano deve ser um número inteiro.',
            'birthdate.min' => 'O ano não pode ser inferior a 1000.',
            'birthdate.max' => 'O ano não pode ser maior que o ano atual.',

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
