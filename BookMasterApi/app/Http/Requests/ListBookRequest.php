<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ListBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'isbn' => 'string|min:10|max:13',
            'publisher' => 'string|min:3|max:255',
            'year' => 'digits:4|integer|min:1000|max:' . date('Y'),
            'author' => 'string|min:3|max:255',
            'category' => 'string|min:3|max:255',
            'search' => 'string|min:3|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'isbn.string' => 'O ISBN deve ser uma sequência de caracteres.',
            'isbn.max' => 'O ISBN não pode ultrapassar 13 caracteres.',
            'isbn.min' => 'O ISBN não pode ser menor que 10 dígitos',

            'publisher.string' => 'O nome da editora deve ser uma sequência de caracteres.',
            'publisher.max' => 'O nome da editora não pode ultrapassar 255 caracteres.',
            'publisher.min' => 'O nome da editora não pode ser menor que 3 caracteres',

            'year.digits' => 'O ano deve conter exatamente 4 dígitos.',
            'year.integer' => 'O ano deve ser um número inteiro.',
            'year.min' => 'O ano não pode ser inferior a 1000.',
            'year.max' => 'O ano não pode ser maior que o ano atual.',

            'author.string' => 'O nome do autor deve ser uma sequência de caracteres.',
            'author.max' => 'O nome do autor não pode ultrapassar 255 caracteres.',
            'author.min' => 'O nome do author não pode ser menor que 3 caracteres',

            'category.string' => 'A categoria deve ser uma sequência de caracteres.',
            'category.max' => 'A categoria não pode ultrapassar 255 caracteres.',
            'category.min' => 'O nome categoria não pode ser menor que 3 caracteres',

            'search.string' => 'A nome do livro deve ser uma sequência de caracteres.',
            'search.max' => 'O nome do autor não pode ultrapassar 255 caracteres.',
            'search.min' => 'O nome do livro não pode ser menor que 3 caracteres'
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
