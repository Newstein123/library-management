<?php

use App\Http\Controllers\Api\v1\BookController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BookController::class, 'index']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('{id}', [BookController::class, 'show']);
    Route::post('create', [BookController::class, 'store']);
    Route::post('edit/{id}', [BookController::class, 'update']);
    Route::delete('delete/{id}', [BookController::class, 'destroy']);
});
