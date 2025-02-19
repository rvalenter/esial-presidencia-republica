<?php

use App\Http\Controllers\Notas\NotaTecnicaController;
use App\Http\Controllers\Posicionamento\PosicionamentoController;
use Illuminate\Support\Facades\Route;

Route::get('/nota-tecnica', [NotaTecnicaController::class, 'index'])
    ->name('nota-tecnica.index')
    ->middleware('permission:8,9,15,10');

Route::get('/posicionamento', [PosicionamentoController::class, 'index'])
    ->name('posicionamento.index')
    ->middleware('permission:5');
