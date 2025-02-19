<?php

use App\Http\Controllers\Notas\NotaTecnicaAiController;
use App\Http\Controllers\Notas\NotaTecnicaAreaTematicaController;
use App\Http\Controllers\Notas\NotaTecnicaArquivoController;
use App\Http\Controllers\Notas\NotaTecnicaController;
use App\Http\Controllers\Notas\NotaTecnicaDashboardController;
use App\Http\Controllers\Notas\NotaTecnicaParametroController;
use App\Http\Controllers\Notas\NotaTecnicaPosicaoController;
use App\Http\Controllers\Notas\NotaTecnicaPreAvalicaoController;
use App\Http\Controllers\Notas\NotaTecnicaReferenciaController;
use App\Http\Controllers\Notas\NotaTecnicaResumoController;
use App\Http\Controllers\Notas\NotaTecnicaSearchController;
use App\Http\Controllers\Notas\NotaTecnicaTextoController;
use Illuminate\Support\Facades\Route;

Route::prefix('nota-tecnica')->group(function () {
    Route::post('listar', NotaTecnicaSearchController::class);

    Route::get('buscar/{notaTecnica}/id', [NotaTecnicaController::class, 'show']);

    Route::post('criar', [NotaTecnicaController::class, 'store']);

    Route::get('referencia/buscar/{esialLegislativoProposicao}/id', [NotaTecnicaController::class, 'showReference']);

    Route::get('texto/buscar/{id}/id', [NotaTecnicaTextoController::class, 'show']);

    Route::get('resumo/buscar/{id}/id', [NotaTecnicaResumoController::class, 'show']);

    Route::post('referencia/salvar', [NotaTecnicaReferenciaController::class, 'store']);

    Route::post('referencia/deletar', [NotaTecnicaReferenciaController::class, 'destroy']);

    Route::get('parametros', [NotaTecnicaParametroController::class, 'index']);

    Route::post('posicao/salvar', [NotaTecnicaPosicaoController::class, 'store']);

    Route::post('posicao-texto/salvar', [NotaTecnicaPosicaoController::class, 'storeText']);

    Route::post('posicao-texto/atualizar', [NotaTecnicaPosicaoController::class, 'updateText']);

    Route::post('ia/gerar', [NotaTecnicaAiController::class, 'generateIA']);

    Route::post('solicitar-avaliacao/{notaTecnica}/id', [NotaTecnicaPreAvalicaoController::class, 'store']);

    Route::post('devolver-avaliacao/{notaTecnica}/id', [NotaTecnicaPreAvalicaoController::class, 'update']);

    Route::post('finalizar/{notaTecnica}/id', [NotaTecnicaController::class, 'finish']);

    Route::get('area-tematica/{text}/buscar', [NotaTecnicaAreaTematicaController::class, 'show']);

    Route::post('area-tematica/salvar', [NotaTecnicaAreaTematicaController::class, 'store']);

    Route::get('comparar/{id}/id', [NotaTecnicaController::class, 'compare']);

    Route::post('referencia/descricao', [NotaTecnicaReferenciaController::class, 'descriptions']);

    Route::post('dashboard', [NotaTecnicaDashboardController::class, 'index']);

    Route::post('tabela', [NotaTecnicaDashboardController::class, 'table']);

    Route::post('arquivo/salvar', [NotaTecnicaArquivoController::class, 'store']);

    Route::get('arquivo/listar/{id}/id', [NotaTecnicaArquivoController::class, 'show']);

    Route::get('arquivo/download/{anexo}/id', [NotaTecnicaArquivoController::class, 'download']);

    Route::get('arquivo/deletar/{id}/id', [NotaTecnicaArquivoController::class, 'destroy']);

    Route::post('area-tematica/deletar', [NotaTecnicaAreaTematicaController::class, 'destroy']);

    Route::post('reciclar/{id}/id', [NotaTecnicaController::class, 'recycle']);

    Route::post('referencia/deletar', [NotaTecnicaPosicaoController::class, 'destroy']);

    Route::post('deletar/{notaTecnica}/id', [NotaTecnicaController::class, 'destroy']);
});
