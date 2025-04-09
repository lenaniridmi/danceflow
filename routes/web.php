<?php

use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', function () {
    return view('app');
});

// Другие страницы, которые рендерятся через Vue
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');