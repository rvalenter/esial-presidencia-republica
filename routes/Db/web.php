<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('db')->group(function () {
    Route::get('/migrate', function () {
        Artisan::call('migrate');

        return 'Migrações executadas com sucesso!';
    })->middleware('permission:3');

    Route::get('/migrate/fresh', function () {
        Artisan::call('migrate:fresh --seed');

        return 'Migrações executadas com sucesso!';
    })->middleware('permission:3');
});
