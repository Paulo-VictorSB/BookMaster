<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAuthorRequest;
use App\Http\Requests\ListAuthorRequest;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Response\Response;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function list(ListAuthorRequest $request)
    {
        $response = new Response();

        $validated = $request->validated();

        $query = Author::query();

        if (isset($validated['name'])) {
            $query->where('title', 'like', '%' . $validated['name'] . '%');
        }

        if (isset($validated['birthdate'])) {
            $query->where('birthdate', 'like', $validated['birthdate'] . '%');
        }

        if (isset($validated['bio'])) {
            $query->where('bio', 'like', '%' . $validated['bio'] . '%');
        }

        $authors = $query->get();

        if ($authors->isEmpty()) {
            return $response
                ->setCode(404)
                ->setData("Não encontramos autores que satisfaçam a sua pesquisa.")
                ->response();
        }

        return $response
            ->setData($authors)
            ->response();
    }

    public function store(StoreAuthorRequest $request)
    {
        $response = new Response();

        try {
            $validated = $request->validated();

            $author = Author::create($validated);

            return $response
                ->setMessage('Autor criado com sucesso')
                ->setData($author)
                ->response();
        } catch (\Exception $e) {
            return $response
                ->setStatus('error')
                ->setCode(500)
                ->setErrorMessage('Erro ao criar um autor' . $e->getMessage())
                ->response();
        }
    }

    public function update(UpdateAuthorRequest $request)
    {
        $response = new Response();

        try {
            $validated = $request->validated();

            $author = Author::find($validated['id']);

            if (!$author) {
                return $response
                    ->setCode(404)
                    ->setErrorMessage("Autor não encontrado")
                    ->response();
            }

            if (isset($validated['name'])) {
                $author->update(['name' => $validated['name']]);
            }

            if (isset($validated['birthdate'])) {
                $author->update(['birthdate' => $validated['birthdate']]);
            }

            if (isset($validated['bio'])) {
                $author->update(['bio' => $validated['bio']]);
            }

            return $response
                ->setMessage('Autor atualizado com sucesso')
                ->setData($author->fresh())
                ->response();
        } catch (\Exception $e) {
            return $response
                ->setStatus('error')
                ->setCode(500)
                ->setErrorMessage('Erro ao atualizar autor' . $e->getMessage())
                ->response();
        }
    }

    public function delete(DeleteAuthorRequest $request)
    {
        $response = new Response();

        try {
            $validated = $request->validated();

            if (isset($validated['id'])) {
                $author = Author::find($validated['id']);
            }

            if (!$author) {
                return $response
                    ->setStatus('error')
                    ->setCode(404)
                    ->setErrorMessage('Autor não encontrado.')
                    ->response();
            }

            if ($author->books()->exists()) {
                return $response
                    ->setStatus('error')
                    ->setCode(409)
                    ->setErrorMessage('Não é possível deletar o autor, pois ele possui livros cadastrados.')
                    ->response();
            }

            $author->forceDelete();

            return $response
                ->setStatus('success')
                ->setCode(200)
                ->setMessage('Autor deletado com sucesso')
                ->response();
        } catch (\Exception $e) {
            return $response
                ->setStatus('error')
                ->setCode(500)
                ->setErrorMessage('Erro ao deletar Autor' . $e->getMessage())
                ->response();
        }
    }
}
