<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListAuthorRequest;
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
            $query->where('name', 'like', '%' . $validated['name'] . '%');
        }

        if (isset($validated['birthdate'])) {
            $query->where('birthdate', 'like',$validated['birthdate'] . '%');
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
}
