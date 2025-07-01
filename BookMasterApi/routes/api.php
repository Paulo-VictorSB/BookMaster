<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PublisherController;
use Illuminate\Support\Facades\Route;

Route::prefix('book')->group(function () {
    Route::get('/list', [BookController::class, 'list']);
    Route::post('/store', [BookController::class, 'store']);
    Route::put('/update', [BookController::class, 'update']);
    Route::delete('/delete', [BookController::class, 'delete']);
});

Route::prefix('author')->group(function () {
    Route::get('/list', [AuthorController::class, 'list']);
    Route::post('/store', [AuthorController::class, 'store']);
    Route::put('/update', [AuthorController::class, 'update']);
    Route::delete('/delete', [AuthorController::class, 'delete']);
});

Route::prefix('publisher')->group(function () {
    Route::get('/list', [PublisherController::class, 'list']);
    Route::post('/store', [PublisherController::class, 'store']);
    Route::put('/update', [PublisherController::class, 'update']);
    Route::delete('/delete', [PublisherController::class, 'delete']);
});