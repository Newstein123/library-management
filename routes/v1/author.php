<?php

use App\Http\Controllers\Api\v1\AuthorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthorController::class, 'index']);
Route::get('{id}', [AuthorController::class, 'show']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin|editor')->group(function () {
    Route::post('create', [AuthorController::class, 'store'])->middleware('permission:create author');
    Route::put('edit/{id}', [AuthorController::class, 'update'])->middleware('permission:edit author');
    Route::delete('delete/{id}', [AuthorController::class, 'delete'])->middleware('permission:delete author');
});
