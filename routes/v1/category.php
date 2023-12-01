<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoryController::class, 'index']);
Route::get('{id}', [CategoryController::class, 'show']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin|editor')->group(function () {
    Route::post('create', [CategoryController::class, 'store'])->middleware('permission:create category');
    Route::put('edit/{id}', [CategoryController::class, 'update'])->middleware('permission:edit category');
    Route::delete('delete/{id}', [CategoryController::class, 'delete'])->middleware('permission:delete category');
});