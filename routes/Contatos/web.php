<?php

use Illuminate\Support\Facades\Route;

Route::get('/contato/{id?}/id', [App\Http\Controllers\Contatos\ContatoController::class, 'index'])
    ->name('contatos.index')
    ->middleware('permission:6');

Route::get('/parlamentares', [App\Http\Controllers\Contatos\ContatoController::class, 'index'])
    ->name('parlamentares.index')
    ->middleware('permission:6');
