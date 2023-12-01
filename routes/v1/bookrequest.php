<?php

use App\Http\Controllers\Api\v1\BookRequestConroller;
use Illuminate\Support\Facades\Route;


Route::prefix('/')->middleware('auth:sanctum', 'role:member')->group(function () {
    Route::get('{id}', [BookRequestConroller::class, 'show']);
});

Route::prefix('/')->middleware('auth:sanctum', 'role:member')->group(function () {
    Route::get('user/{id}', [BookRequestConroller::class, 'get_user_bookrequest']);
    Route::post('create', [BookRequestConroller::class, 'store']);
});

Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::get('/', [BookRequestConroller::class, 'index']);
    Route::put('edit/{id}', [BookRequestConroller::class, 'update']);
});