<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListPublisherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'country' => 'string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'O nome deve ser uma sequência de caracteres',
            'name.max' => 'O nome não pode ultrapassar de 255 caracteres',

            'country.string' => 'O nome deve ser uma sequência de caracteres',
            'country.max' => 'O nome não pode ultrapassar de 255 caracteres',
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
