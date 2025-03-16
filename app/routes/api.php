<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('posts', PostController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('comments', CommentController::class);
