<?php

use Illuminate\Support\Facades\Route;


Route::middleware('api-key')->group(function() {
    Route::get('/server/{subnet}', [\App\Http\Controllers\ServerController::class,'server']);
    Route::get('/servers', [\App\Http\Controllers\ServerController::class,'servers']);
});
