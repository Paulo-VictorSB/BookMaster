<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Response\Response;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;

class BookController extends Controller
{
    public function list(ListBookRequest $request)
    {
        $response = new Response();

        $validated = $request->validated();

        $query = Book::query()->with(['authors', 'categories', 'publisher']);

        if (isset($validated['search'])) {
            $query->where('title', 'like', '%' . $validated['search'] . '%');
        }

        if (isset($validated['isbn'])) {
            $query->where('isbn', '=', $validated['isbn']);
        }

        if (isset($validated['year'])) {
            $query->where('release_year', '=', $validated['year']);
        }

        if (isset($validated['category'])) {
            $query->whereHas('categories', function ($q) use ($validated) {
                $q->where('name', '=', $validated['category']);
            });
        }

        if (isset($validated['author'])) {
            $query->whereHas('authors', function ($q) use ($validated) {
                $q->where('name', 'like', '%' . $validated['author'] . '%');
            });
        }

        if (isset($validated['publisher'])) {
            $query->whereHas('publisher', function ($q) use ($validated) {
                $q->where('name', 'like', '%' . $validated['publisher'] . '%');
            });
        }

        $books = $query->get();        

        if ($books->isEmpty()) {
            return $response
                ->setCode(404)
                ->setData("Não encontramos livros que satisfaçam a sua pesquisa.")
                ->response();
        }

        return $response
            ->setData($books)
            ->response();
    }

    public function store(StoreBookRequest $request)
    {
        $response = new Response();

        try {
            $validated = $request->validated();

            $author = Author::firstOrCreate(
                ['name' => $validated['author']],
                [
                    'birthdate' => $validated['authorBirthdate'],
                    'bio' => $validated['authorBio']
                ]
            );

            $publisher = Publisher::firstOrCreate(
                ['name' => $validated['publisher']],
                ['country' => $validated['publisherCountry'] ?? null]
            );

            $category = Category::firstOrCreate(
                ['name' => $validated['category']]
            );

            $book = Book::create([
                'title' => $validated['title'],
                'isbn' => $validated['isbn'],
                'publisher_id' => $publisher->id,
                'release_year' => $validated['releaseYear'],
                'description' => $validated['description']
            ]);

            $book->authors()->attach($author->id);
            $book->categories()->attach($category->id);

            return $response
                ->setMessage('Livro criado com sucesso')
                ->setData($book)
                ->response();
        } catch (\Exception $e) {
            return $response
                ->setStatus('error')
                ->setCode(500)
                ->setErrorMessage('Erro ao criar livro' . $e->getMessage())
                ->response();
        }
    }
}
