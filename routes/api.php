<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\News\NewsController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/articles', [NewsController::class, 'getArticles']);
    Route::get('/top-headlines', [NewsController::class, 'getTopHeadlines']);
});
