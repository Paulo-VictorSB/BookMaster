<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePublisherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:255',
            'country' => 'required|string|min:3|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma sequência de caracteres.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
            'name.max' => 'O nome não pode ultrapassar 255 caracteres.',

            'country.required' => 'O campo país é obrigatório.',
            'country.string' => 'O país deve ser uma sequência de caracteres.',
            'country.min' => 'O país deve ter no mínimo 3 caracteres.',
            'country.max' => 'O país não pode ultrapassar 255 caracteres.',
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
