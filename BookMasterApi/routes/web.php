<?php

use App\Models\Book;
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