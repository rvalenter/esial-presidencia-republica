<?php

use App\Http\Controllers\Evento\EventoController;
use Illuminate\Support\Facades\Route;

Route::get('/calendario/autocomplete/{user}', [EventoController::class, 'autocomplete'])
    ->name('calendario.autocomplete');

Route::post('/calendario/legislativo/autocomplete', [EventoController::class, 'legislativoAutocomplete'])
    ->name('calendario.legislativo.autocomplete');

Route::post('/calendario/salvar', [EventoController::class, 'store'])
    ->name('calendario.salvar');

Route::get('/calendario/eventos/{mounth?}', [EventoController::class, 'index'])
    ->name('calendario.eventos');

Route::post('/calendario/apagar', [EventoController::class, 'destroy'])
    ->name('calendario.apagar');

Route::get('/calendario/evento/{id}', [EventoController::class, 'show'])
    ->name('calendario.evento');

Route::post('/calendario/atualizar', [EventoController::class, 'update'])
    ->name('calendario.atualizar');

Route::post('/calendario/colegiados', [EventoController::class, 'listCollegiate']);
