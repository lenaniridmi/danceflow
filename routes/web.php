<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\FavoritesController;

Route::get('/', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

Route::prefix('api/auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('google/callback', [AuthController::class, 'handleGoogleCallback']);
    Route::get('yandex/callback', [AuthController::class, 'handleYandexCallback']);
    Route::get('mailru/callback', [AuthController::class, 'handleMailruCallback']);
});

Route::prefix('api')->group(function () {
    Route::get('reviews', [ReviewsController::class, 'index']);
    Route::post('reviews', [ReviewsController::class, 'store']);
    Route::get('courses', [CoursesController::class, 'apiIndex']);
    Route::get('courses/{id}', [CoursesController::class, 'show']);
    Route::post('favorites', [FavoritesController::class, 'store']);
    Route::delete('favorites/{courseId}', [FavoritesController::class, 'destroy']);
});