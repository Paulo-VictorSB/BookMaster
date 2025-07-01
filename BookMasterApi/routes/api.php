<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::prefix('book')->group(function () {
    Route::get('/list', [BookController::class, 'list']);
    Route::post('/store', [BookController::class, 'store']);
    Route::put('/update', [BookController::class, 'update']);
    Route::delete('/delete', [BookController::class, 'delete']);
});