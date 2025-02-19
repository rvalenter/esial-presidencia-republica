<?php

use App\Http\Controllers\Usuarios\SegurancaController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')
    ->group(function () {
        require __DIR__ . '/Users/api.php';
        require __DIR__ . '/Calendario/api.php';
        require __DIR__ . '/Contatos/api.php';
        require __DIR__ . '/Nota/api.php';
        require __DIR__ . '/Relatorio/api.php';
        require __DIR__ . '/Manager/api.php';
    })
    ->middleware('auth:sanctum');
