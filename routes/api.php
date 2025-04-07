<?php

use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfilController;


Route::get('content', [GalleryController::class, 'index']);
Route::get('content/{id}', [GalleryController::class, 'show']);
Route::post('content', [GalleryController::class, 'store']);
Route::patch('content/{id}', [GalleryController::class, 'update']);
Route::delete('content/{id}', [GalleryController::class, 'destroy']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/check', [AuthController::class, 'check']);

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/update-user', [AuthController::class, 'updateUser']);
    Route::delete('/delete-user', [AuthController::class, 'deleteUser']);
});


Route::get('category', [CategoryController::class, 'index']);
Route::get('category/{id}', [CategoryController::class, 'show']);
Route::post('category', [CategoryController::class, 'store']);
Route::patch('category/{id}', [CategoryController::class, 'update']);
Route::delete('category/{id}', [CategoryController::class, 'destroy']);


Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store']);
Route::patch('/events/{id}', [EventController::class, 'update']);
Route::delete('/events/{id}', [EventController::class, 'destroy']);


Route::get('/comments', [CommentController::class, 'index']);
Route::get('/comments/{id}', [CommentController::class, 'show']);
Route::post('/comments', [CommentController::class, 'store']);
Route::put('/comments/{id}', [CommentController::class, 'update']);
Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

Route::get('/profil', [ProfilController::class, 'index']);
Route::get('/profil/{id}', [ProfilController::class, 'show']);
Route::post('/profil', [ProfilController::class, 'store']);
Route::patch('/profil/{id}', [ProfilController::class, 'update']);
Route::delete('/profil/{id}', [ProfilController::class, 'destroy']);