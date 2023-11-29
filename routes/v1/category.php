<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoryController::class, 'index']);
Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/get-category', [CategoryController::class, 'index']);
});