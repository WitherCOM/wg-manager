<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth.filament')
    ->group(function() {
        Route::get('/image/{peer}',[\App\Http\Controllers\QrController::class,'image']);
    });
