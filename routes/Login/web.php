<?php

use App\Http\Controllers\GovBR\AutenticarController;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Usuarios\CadastroController;
use App\Http\Controllers\Usuarios\SegurancaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'index'])
    ->name('login');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->name('logout');

Route::post('/emitir-token', [LoginController::class, 'token'])
    ->name('login.send.token');

Route::post('/validar-token', [LoginController::class, 'validateToken'])
    ->name('login.validate.token');

Route::post('/validar-email-unico', [LoginController::class, 'validateEmail'])
    ->name('login.validate.email');

Route::post('/login', [LoginController::class, 'getAuth']);

Route::post('/validar-email', [CadastroController::class, 'validateUser']);

Route::post('/cadastro-inicial', [CadastroController::class, 'storeUser']);

Route::get('/govbr', [AutenticarController::class, 'getAuth']);

Route::get('/govbr/autenticacao', [AutenticarController::class, 'setAuth']);

Route::get('/seguranca/{cpf}/cpf', [SegurancaController::class, 'cpf']);
