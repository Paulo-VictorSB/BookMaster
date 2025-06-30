<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'title' => 'string|min:3|max:255',
            'isbn' => 'string|min:10|max:13',
            'description' => 'string|max:255',
            'releaseYear' => 'integer|digits:4|min:1000|max:' . date('Y')
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'O ID do livro é obrigatório.',
            'id.integer' => 'O ID deve ser um número inteiro.',

            'title.string' => 'O título deve ser uma sequência de caracteres.',
            'title.min' => 'O título deve conter no mínimo 3 caracteres.',
            'title.max' => 'O título não pode ultrapassar 255 caracteres.',

            'isbn.string' => 'O ISBN deve ser uma sequência de caracteres.',
            'isbn.min' => 'O ISBN deve conter no mínimo 10 caracteres.',
            'isbn.max' => 'O ISBN não pode ultrapassar 13 caracteres.',

            'description.string' => 'A descrição deve ser uma sequência de caracteres.',
            'description.max' => 'A descrição não pode ultrapassar 255 caracteres.',

            'release_year.integer' => 'O ano de lançamento deve ser um número inteiro.',
            'release_year.digits' => 'O ano de lançamento deve conter exatamente 4 dígitos.',
            'release_year.min' => 'O ano de lançamento não pode ser inferior a 1000.',
            'release_year.max' => 'O ano de lançamento não pode ser maior que o ano atual.',
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
