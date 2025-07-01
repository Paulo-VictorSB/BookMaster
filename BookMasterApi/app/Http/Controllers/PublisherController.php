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
}
