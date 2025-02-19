<?php

use App\Http\Controllers\Relatorio\NotaTecnicaController;
use App\Http\Controllers\Relatorio\RelatorioComissaoController;
use App\Http\Controllers\Relatorio\RelatorioController;
use App\Http\Controllers\Relatorio\RelatorioPerfilParlamentarController;
use Illuminate\Support\Facades\Route;

Route::prefix('relatorios')->group(function () {
    Route::get('/', [RelatorioController::class, 'index'])
        ->name('relatorio.web.index');

    Route::get('/perfil-parlamentar/{id}/id', [RelatorioPerfilParlamentarController::class, 'show'])
        ->name('relatorio.perfil-parlamentar');

    Route::get('/comissao/{id}/id', [RelatorioComissaoController::class, 'show'])
        ->name('relatorio.comissoes-camara');

    Route::get('/perfil-parlamentar/pdf/{id?}/id', [RelatorioPerfilParlamentarController::class, 'download'])
        ->name('relatorio.perfil-parlamentar.pdf');

    Route::get('/comissao/pdf/{id}/id', [RelatorioComissaoController::class, 'download'])
        ->name('relatorio.comissoes-camara.pdf');

    Route::get('/nota-tecnica/pdf/{id}/id', [NotaTecnicaController::class, 'download'])
        ->name('relatorio.nota-tecnica.pdf');
});
