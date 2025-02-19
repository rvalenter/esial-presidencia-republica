<?php

use App\Http\Controllers\Acessos\GrupoController;
use App\Http\Controllers\Usuarios\AcessosController;
use App\Http\Controllers\Usuarios\CadastroController;
use App\Http\Controllers\Usuarios\CargoController;
use App\Http\Controllers\Usuarios\NiveisAcessoController;
use App\Http\Controllers\Usuarios\NotificacaoController;
use App\Http\Controllers\Usuarios\PerfilAutoCompleteController;
use App\Http\Controllers\Usuarios\PerfilController;
use App\Http\Controllers\Usuarios\PerfilDescricaoController;
use App\Http\Controllers\Usuarios\SegurancaController;
use App\Http\Controllers\Usuarios\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('/perfil/{id}/id', [PerfilController::class, 'show'])->name('api.usuario.perfil');

Route::get('/usuario', [PerfilController::class, 'showUser'])->name('usuario');

Route::post('/consultar-cadastro', [CadastroController::class, 'show'])->name('gestao.checar.cadastro');

Route::post('/criar-cadastro', [CadastroController::class, 'store'])->name('gestao.criar.cadastro');

Route::post('/consultar-cargos', [CargoController::class, 'show']);

Route::get('/usuario/{id}/id', [UsuariosController::class, 'acessos'])->name('gestao.checar.acessos');

Route::get('/usuario/acessos', [NiveisAcessoController::class, 'index'])->name('gestao.usuario.tipo');

Route::get('/usuario/acessos/{group}/grupo', [NiveisAcessoController::class, 'show'])->name('gestao.usuario.tipo.checados');

Route::post('/usuario/salvar-acesso', [UsuariosController::class, 'store']);

Route::post('/usuario/remover-acesso', [UsuariosController::class, 'destroy']);

Route::post('/usuario/atualizar-foto', [PerfilController::class, 'storePhoto']);

Route::get('/usuario/exibir-foto/{id}/id', [PerfilController::class, 'showPhoto']);

Route::get('/usuario/todos', [PerfilController::class, 'registeredUsers'])->name('gestao.usuario.cadastrados.id');

Route::get('/usuario/cadastrados/{user?}/id', [PerfilController::class, 'registeredUsers'])
    ->name('gestao.usuario.cadastrados');

Route::get('/usuario/{id}/remover', [PerfilController::class, 'destroy']);

Route::post('/atualizar-cadastro', [PerfilController::class, 'update']);

Route::get('/usuario/autocomplete/{type}/{search}', [PerfilAutoCompleteController::class, 'show'])->name('gestao.usuario.autocomplete');

Route::get('/usuario/autocompletar-grupo-acesso/{group}', [GrupoController::class, 'show'])->name('gestao.grupos.autocomplete');

Route::post('/usuario/criar-grupo-acesso', [GrupoController::class, 'store'])->name('gestao.grupos.criar');

Route::get('/usuario/grupo-acesso', [GrupoController::class, 'index'])->name('gestao.grupos');

Route::get('/usuario/grupo-acesso-usuario/{id}/id', [GrupoController::class, 'showByUser'])->name('gestao.grupos.usuario');

Route::post('/usuario/criar-funcional', [PerfilAutoCompleteController::class, 'store'])->name('gestao.usuario.criar.funcional');

Route::post('/usuario/atualizar-email', [PerfilController::class, 'updateEmail']);

Route::post('/usuario/enviar-codigo/atualizar-email', [PerfilController::class, 'sendCodeUpdateEmail']);

Route::get('/usuario/{id}/descricao', [PerfilDescricaoController::class, 'show']);

Route::post('/usuario/criar-apresentacao', [PerfilDescricaoController::class, 'store']);

Route::post('/usuario/adicionar-acesso-grupo', [GrupoController::class, 'storeGroupAccess']);

Route::post('/usuario/remover-acesso-grupo', [GrupoController::class, 'detroyGroupAccess']);

Route::post('/usuario/adicionar-grupo-usuario', [GrupoController::class, 'storeUserGroup']);

Route::post('/usuario/criar-acesso', [AcessosController::class, 'store']);

Route::get('/usuario/todos-acessos/{nome}/acesso-nome', [AcessosController::class, 'index']);

Route::post('/usuario/apagar-acesso', [AcessosController::class, 'destroy']);

Route::get('/usuario/acessos-usuario/{id}/id', [AcessosController::class, 'show']);

Route::get('/usuario/notificacoes/{filter}/filtro', [NotificacaoController::class, 'show']);

Route::get('/usuario/tenho-notificacoes', [NotificacaoController::class, 'hasNotification']);

Route::post('/usuario/arquivar-notificacao', [NotificacaoController::class, 'archive']);

Route::post('/usuario/salvar-seguranca', [SegurancaController::class, 'store']);

Route::get('/usuario/seguranca/{id}/id', [SegurancaController::class, 'show']);
