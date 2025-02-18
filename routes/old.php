<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContentController;

Route::middleware('auth:api')->group(function () {
    Route::get('content', [ContentController::class, 'index']);
    Route::get('content/{id}', [ContentController::class, 'show']);
    Route::post('content', [ContentController::class, 'store']);
    Route::put('content/{id}', [ContentController::class, 'update']);
    Route::delete('content/{id}', [ContentController::class, 'destroy']);
});