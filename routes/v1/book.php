<?php

use App\Http\Controllers\Api\v1\BookController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BookController::class, 'index']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/{id}', [BookController::class, 'show']);
    Route::delete('delete/{id}', [BookController::class, 'destroy']);
    Route::put('change-status/{id}', [BookController::class, 'change_status']);
});
