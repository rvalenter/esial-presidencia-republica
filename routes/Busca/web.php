<?php

use Illuminate\Support\Facades\Route;

Route::prefix('busca')->group(function () {
    Route::get('/{busca?}', [App\Http\Controllers\Busca\BuscaController::class, 'show'])
        ->name('busca.index')
        ->middleware('permission:17');
});
