<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeletePublisherRequest;
use App\Http\Requests\ListPublisherRequest;
use App\Http\Requests\StorePublisherRequest;
use App\Http\Requests\UpdatePublisherRequest;
use App\Http\Response\Response;
use App\Models\Publisher;

class PublisherController extends Controller
{
    public function list(ListPublisherRequest $request)
    {
        $response = new Response();

        $validated = $request->validated();

        $query = Publisher::query();

        if (isset($validated['name'])) {
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        if (isset($validated['country'])) {
            $query->where('country', 'like', '%' . $validated['country'] . '%');
        }

        $publishers = $query->get();

        if ($publishers->isEmpty()) {
            return $response
                ->setCode(404)
                ->setData("Não encontramos publicados que satisfaçam a sua pesquisa")
                ->response();
        }

        return $response
            ->setData($publishers)
            ->response();
    }

    public function store(StorePublisherRequest $request)
    {
        $response = new Response();

        try {
            $validated = $request->validated();

            $publisher = Publisher::create($validated);

            return $response
                ->setMessage('Autor criado com sucesso')
                ->setData($publisher)
                ->response();
        } catch (\Exception $e) {
            return $response
                ->setStatus('error')
                ->setCode(500)
                ->setErrorMessage('Erro ao criar um autor' . $e->getMessage())
                ->response();
        }
    }

    public function update(UpdatePublisherRequest $request)
    {
        $response = new Response();

        try {
            $validated = $request->validated();

            $publisher = Publisher::find($validated['id']);

            if (!$publisher) {
                return $response
                    ->setCode(404)
                    ->setErrorMessage("Editora não encontrada")
                    ->response();
            }

            if (isset($validated['name'])) {
                $publisher->update(['name' => $validated['name']]);
            }

            if (isset($validated['country'])) {
                $publisher->update(['country' => $validated['country']]);
            }

            return $response
                ->setMessage('Autor atualizado com sucesso')
                ->setData($publisher->fresh())
                ->response();
        } catch (\Exception $e) {
            return $response
                ->setStatus('error')
                ->setCode(500)
                ->setErrorMessage('Erro ao atualizar autor' . $e->getMessage())
                ->response();
        }
    }
}
