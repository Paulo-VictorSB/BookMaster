<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Response\Response;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        return (new Response)->setData($books)->response();
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
                ->setCode(200)
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
