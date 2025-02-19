<?php

use App\Http\Controllers\Relatorio\RelatorioComissaoController;
use App\Http\Controllers\Relatorio\RelatorioController;
use App\Http\Controllers\Relatorio\RelatorioPerfilParlamentarController;
use Illuminate\Support\Facades\Route;

Route::prefix('relatorio')->group(function () {
    Route::middleware(['permission:13'])->group(function () {
        Route::get('lista', [RelatorioController::class, 'index'])
            ->name('relatorio.index');

        Route::get('lista/{id?}', [RelatorioPerfilParlamentarController::class, 'list']);

        Route::get('comissao', [RelatorioComissaoController::class, 'index']);
    });
    Route::middleware(['permission:14'])->group(function () {
        Route::post('salvar', [RelatorioController::class, 'store'])
            ->name('relatorio.store');
    });
});
