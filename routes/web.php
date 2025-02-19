<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\RatingController;
use Illuminate\Support\Facades\Route;

Route::middleware('json')->group(function () {
    Route::get('/movie', [MovieController::class, 'index']);
    Route::get('/movie/{movie}', [MovieController::class, 'get']);
    Route::get('/movie/{movie}/rating', [RatingController::class, 'index']);
    Route::put('/movie/{movie}/rating', [RatingController::class, 'create']);
});
