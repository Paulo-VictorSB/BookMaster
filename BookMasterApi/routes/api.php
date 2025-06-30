<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('book/list', [BookController::class, 'list']);
Route::post('book/store', [BookController::class, 'store']);
Route::put('book/update', [BookController::class, 'update']);