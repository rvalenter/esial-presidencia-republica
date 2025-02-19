<?php

use Illuminate\Support\Facades\Route;

Route::prefix('webservice')->group(function () {
    Route::get('/bancadas/salvar', [\App\Http\Controllers\WebServices\Bancadas::class, 'store']);

    Route::get('/frentes/salvar', [\App\Http\Controllers\WebServices\FrentesParlamentaresController::class, 'store']);

    Route::get('/proposicoes/{id}/consultar', [\App\Http\Controllers\WebServices\ProposicoesController::class, 'show']);

    Route::get('/votacoes/{id}/parlamentar/{casa}/casa/consultar', [\App\Http\Controllers\WebServices\VotacoesController::class, 'show']);
});
