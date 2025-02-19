<?php

use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Usuarios\PerfilController;
use App\Http\Controllers\Usuarios\UsuariosController;

Route::get('/home', [HomeController::class, 'index'])
    ->name('home');

Route::get('/usuarios', [UsuariosController::class, 'index'])
    ->name('gestao.usuarios')
    ->middleware('permission:2');

Route::get('/usuarios/{cpf}/cpf', [UsuariosController::class, 'show'])
    ->name('gestao.usuarios.cpf')
    ->middleware('permission:2');

Route::get('perfil/{id?}', [PerfilController::class, 'index'])
    ->name('usuario.perfil');

Route::get('perfil/{id?}/id', [PerfilController::class, 'index'])
    ->name('usuario.perfil.id');
