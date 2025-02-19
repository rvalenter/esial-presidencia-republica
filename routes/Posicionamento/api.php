<?php

use Illuminate\Support\Facades\Route;

Route::prefix('posicionamento')->group(function () {
    Route::middleware(['permission:8'])->group(function () {

    });
    Route::middleware(['permission:9'])->group(function () {

    });
    Route::middleware(['permission:5'])->group(function () {

    });
});
