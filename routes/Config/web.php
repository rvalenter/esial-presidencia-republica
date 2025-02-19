<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::prefix('config')->group(function () {
    Route::get('/reset', function () {
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return 'Configurações resetadas com sucesso!';
    })->middleware('permission:3');

    Route::get('/cache/clear', function () {
        Artisan::call('cache:clear');

        return 'Cache apagado com sucesso!';
    })->middleware('permission:3');

    Route::get('/view', function () {
        Artisan::call('view:clear');
        Artisan::call('view:cache');

        return 'Cache das views gerado com sucesso!';
    })->middleware('permission:3');

    Route::get('/optimize', function () {
        Artisan::call('optimize');

        return 'Otimização concluída com sucesso!';
    })->middleware('permission:3');

    Route::get('/optimize/clear', function () {
        Artisan::call('optimize:clear');

        return 'Otimização excluída com sucesso!';
    })->middleware('permission:3');
});
