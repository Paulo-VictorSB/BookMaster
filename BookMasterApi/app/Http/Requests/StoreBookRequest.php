<?php

namespace App\Http\Requests;

use App\Http\Response\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'publisher' => 'required|string|max:255',
            'publisherCountry' => 'nullable|string|max:255',
            'releaseYear' => 'required|digits:4|integer|min:1000|max:' . date('Y'),
            'description' => 'required|string',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'authorBirthdate' => 'required|date|before:today',
            'authorBio' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título do livro é obrigatório.',
            'title.string' => 'O título do livro deve ser uma sequência de caracteres.',
            'title.max' => 'O título do livro não pode ultrapassar 255 caracteres.',

            'isbn.required' => 'O ISBN é obrigatório.',
            'isbn.string' => 'O ISBN deve ser uma sequência de caracteres.',
            'isbn.unique' => 'Este ISBN já está cadastrado.',

            'publisher.required' => 'O nome da publicadora é obrigatório.',
            'publisher.string' => 'O nome da publicadora deve ser uma sequência de caracteres.',
            'publisher.max' => 'O nome da publicadora não pode ultrapassar 255 caracteres.',

            'releaseYear.required' => 'O ano de lançamento é obrigatório.',
            'releaseYear.digits' => 'O ano de lançamento deve conter exatamente 4 dígitos.',
            'releaseYear.integer' => 'O ano de lançamento deve ser um número inteiro.',
            'releaseYear.min' => 'O ano de lançamento não pode ser inferior a 1000.',
            'releaseYear.max' => 'O ano de lançamento não pode ser maior que o ano atual.',

            'description.required' => 'A descrição é obrigatória.',
            'description.string' => 'A descrição deve ser uma sequência de caracteres.',

            'author.required' => 'O nome do autor é obrigatório.',
            'author.string' => 'O nome do autor deve ser uma sequência de caracteres.',
            'author.max' => 'O nome do autor não pode ultrapassar 255 caracteres.',

            'category.required' => 'A categoria é obrigatória.',
            'category.string' => 'A categoria deve ser uma sequência de caracteres.',
            'category.max' => 'A categoria não pode ultrapassar 255 caracteres.',

            'authorBirthdate.required' => 'A data de nascimento do autor é obrigatória.',
            'authorBirthdate.date' => 'A data de nascimento do autor deve ser uma data válida.',
            'authorBirthdate.before' => 'A data de nascimento do autor deve ser anterior a hoje.',

            'authorBio.required' => 'A biografia do autor é obrigatória.',
            'authorBio.string' => 'A biografia do autor deve ser um texto válido.',

            'publisherCountry.string' => 'O país da publicadora deve ser uma sequência de caracteres.',
            'publisherCountry.max' => 'O país da publicadora não pode ultrapassar 255 caracteres.',
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
