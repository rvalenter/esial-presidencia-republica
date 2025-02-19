<?php

use App\Http\Controllers\Contatos\ContatoController;
use App\Http\Controllers\Contatos\DiscursoController;
use App\Http\Controllers\Contatos\GovernoController;
use App\Http\Controllers\Contatos\ParlamentaresController;
use App\Http\Controllers\Contatos\RelacionametoController;
use Illuminate\Support\Facades\Route;

Route::post('/contato/salvar', [ContatoController::class, 'store']);

Route::post('/contato/atualizar', [ContatoController::class, 'update']);

Route::post('/contato/excluir', [ContatoController::class, 'destroy']);

Route::get('/contato/exibir/{id}/id', [ContatoController::class, 'show']);

Route::get('/contato/listar/{type}/tipo/{search?}/pesquisa', [ContatoController::class, 'list']);

Route::get('/contato/listar_relacionados/{contact}/contato', [ContatoController::class, 'search']);

Route::post('/contato/grupo/salvar', [ContatoController::class, 'storeGroup']);

Route::get('/contato/grupo/listar/{search?}/pesquisa', [ContatoController::class, 'listGroup']);

Route::get('/contato/relacionamento/{esialContato}/id', [RelacionametoController::class, 'show']);

Route::post('/contato/grupo/excluir', [ContatoController::class, 'destroyGroup']);

Route::get('/contato/governo/{casa}/casa/{partido}/partido', [GovernoController::class, 'index']);

Route::get('/contato/partidos', [GovernoController::class, 'listPartidos']);

Route::get('/contato/{id}/id/{type}/tipo/discursos', [DiscursoController::class, 'show']);

Route::post('/contato/analise-discursos', [DiscursoController::class, 'analyse']);

Route::post('/contato/parlamentares', [ParlamentaresController::class, 'show']);
