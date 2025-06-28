<?php

namespace App\Http\Controllers;

use App\Http\Response\Response;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();

        return (new Response)->setData($books)->response();
    }
}