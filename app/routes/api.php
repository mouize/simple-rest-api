<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('signed')
    ->get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->name('verification.verify');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('comments', CommentController::class);

    Route::post('/logout', [AuthController::class, 'logout']);
});
