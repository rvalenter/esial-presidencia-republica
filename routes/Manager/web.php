<?php

use App\Http\Controllers\Manager\AgendaController;
use App\Http\Controllers\Manager\PropositionController;
use Illuminate\Support\Facades\Route;

Route::prefix('manager')->group(function () {
    Route::prefix('posicoes')->group(function () {
        Route::get('/', [PropositionController::class, 'index'])->name('manager.position.index');
    })->middleware('permission:19');

    Route::prefix('pautas')->group(function () {
        Route::get('/', [AgendaController::class, 'index'])->name('manager.agenda.index');
    })->middleware('permission:20');
});
