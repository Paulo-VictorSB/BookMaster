<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Publisher::factory(5)->create();

        $authors = Author::factory(15)->create();

        $categories = Category::factory(10)->create();

        Book::factory(30)->create()
            ->each(function ($book) use ($authors, $categories) {
                $book->authors()
                    ->attach($authors->random(rand(1, 3))
                        ->pluck('id')
                        ->toArray());

                $book->categories()
                    ->attach($categories->random(rand(1, 2))
                        ->pluck('id')
                        ->toArray());
            });
    }
}
