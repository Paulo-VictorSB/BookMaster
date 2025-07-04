<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages/home');
});

Route::get('/details', function (Request $request) {
    $id = $request->query('id');
    $book = Book::with(['authors', 'categories', 'publisher'])->findOrFail($id);
    return view('pages/book', compact('book'));
});

Route::get('/store', function () {
    $publishers = Publisher::all();
    $categories = Category::all();
    $authors = Author::all();

    return view('pages/store', compact('publishers', 'categories', 'authors'));
});