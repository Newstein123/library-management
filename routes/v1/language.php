<?php

use App\Http\Controllers\Api\v1\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LanguageController::class, 'index']);
Route::get('{id}', [LanguageController::class, 'show']);

Route::prefix('/')->middleware('auth:sanctum', 'role:admin')->group(function () {
    Route::post('create', [LanguageController::class, 'store'])->middleware('permission:create language');
    Route::put('edit/{id}', [LanguageController::class, 'update'])->middleware('permission:edit language');
    Route::delete('delete/{id}', [LanguageController::class, 'delete'])->middleware('permission:delete language');
});
