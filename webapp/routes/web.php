<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/image/{peer}',[\App\Http\Controllers\QrController::class,'image']);
