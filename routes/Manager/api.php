<?php

use Illuminate\Support\Facades\Route;

Route::prefix('gestor')->group(function () {
    Route::post('autocomplete', [App\Http\Controllers\Manager\PropositionAutoCompleteController::class, 'index']);
    Route::get('proposicao/{id}/id', [App\Http\Controllers\Manager\PropositionAutoCompleteController::class, 'show']);
    Route::get('/proposicao/{esialLegislativoProposicao}/explicacao', App\Http\Controllers\Manager\PropositionExplanationController::class);
    Route::get('/proposicao/assessores', App\Http\Controllers\Manager\PropositionAssessorController::class);
    Route::post('/proposicao/criar', [App\Http\Controllers\Manager\PropositionController::class, 'store']);
    Route::get('/proposicao/{id}/dados-gerais', [App\Http\Controllers\Manager\PropositionController::class, 'show']);
    Route::get('/proposicao/{id}/historico', App\Http\Controllers\Manager\PropositionTimeLineController::class);
    Route::post('/proposicao/objetivo', [App\Http\Controllers\Manager\PropositionObjectiveController::class, 'store']);
    Route::post('/proposicao/alias', App\Http\Controllers\Manager\PropositionAliasController::class);
    Route::post('/proposicao/comentario/adicionar', [App\Http\Controllers\Manager\PropositionCommentController::class, 'store']);
    Route::get('/proposicao/{propositionId}/proposicao/{committeeId}/comissao/comentarios', [App\Http\Controllers\Manager\PropositionCommentController::class, 'show']);
    Route::get('/proposicao/comentario/{name}/contato', App\Http\Controllers\Manager\EsialGestorProposicaoComentarioContatoController::class);
    Route::get('/proposicao/{id}/objetivo', [App\Http\Controllers\Manager\PropositionObjectiveController::class, 'show']);
    Route::post('/proposicao/governo/adicionar', [App\Http\Controllers\Manager\PropositionGovernmentController::class, 'store']);
    Route::post('/proposicao/parecer/adicionar', [App\Http\Controllers\Manager\ParecerController::class, 'store']);
    Route::get('/proposicao/governo/id/{id}/comite/{comiteid}/posicao', [App\Http\Controllers\Manager\PropositionGovernmentController::class, 'show']);
    Route::get('/proposicao/{id}/proposicao/{comiteid}/comissao/parecer', [App\Http\Controllers\Manager\ParecerController::class, 'show']);
    Route::get('/proposicao/{id}/proposicao/{comissaoid}/comissao/parecer/historico', [App\Http\Controllers\Manager\ParecerController::class, 'history']);
});
