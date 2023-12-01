<?php

use App\Http\Controllers\Api\v1\PublisherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublisherController::class, 'index']);
Route::get('{id}', [PublisherController::class, 'show']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin|editor')->group(function () {
    Route::post('create', [PublisherController::class, 'store'])->middleware('permission:create publisher');
    Route::put('edit/{id}', [PublisherController::class, 'update'])->middleware('permission:edit publisher');
    Route::delete('delete/{id}', [PublisherController::class, 'delete'])->middleware('permission:delete publisher');
});
