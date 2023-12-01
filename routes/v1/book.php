<?php

use App\Http\Controllers\Api\v1\BookController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BookController::class, 'index']);
Route::get('{id}', [BookController::class, 'show']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin|editor')->group(function () {
    Route::post('create', [BookController::class, 'store'])->middleware('permission:create book');
    Route::post('edit/{id}', [BookController::class, 'update'])->middleware('permission:edit book');
    Route::delete('delete/{id}', [BookController::class, 'destroy'])->middleware('permission:delete author');
});
