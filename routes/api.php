<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;


// Gallery routes
Route::get('content', [GalleryController::class, 'index']);
Route::get('content/{id}', [GalleryController::class, 'show']);
Route::post('content', [GalleryController::class, 'store']);
Route::put('content/{id}', [GalleryController::class, 'update']);
Route::delete('content/{id}', [GalleryController::class, 'destroy']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/check', [AuthController::class, 'check']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/update-profile-photo', [AuthController::class, 'updateProfilePhoto']);
    Route::put('/update-user', [AuthController::class, 'updateUser']);
    Route::delete('/delete-user', [AuthController::class, 'deleteUser']);

    Route::get('/user', function (Request $request) {
        return response()->json(['user' => $request->user()]);
    });
});

// Category routes
Route::get('category', [CategoryController::class, 'index']);
Route::get('category/{id}', [CategoryController::class, 'show']);
Route::post('category', [CategoryController::class, 'store']);
Route::put('category/{id}', [CategoryController::class, 'update']);
Route::delete('category/{id}', [CategoryController::class, 'destroy']);

// Event routes
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::put('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);

