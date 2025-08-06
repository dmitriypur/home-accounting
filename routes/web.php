<?php

use Illuminate\Support\Facades\Route;

// SPA маршрут - все запросы направляем на Vue.js приложение
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
