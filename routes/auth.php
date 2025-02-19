<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    require __DIR__.'/Users/web.php';
    require __DIR__.'/Contatos/web.php';
    require __DIR__.'/Config/web.php';
    require __DIR__.'/Posicionamento/web.php';
    require __DIR__.'/Db/web.php';
    require __DIR__.'/Relatorio/web.php';
    require __DIR__.'/WebServices/api.php';
    require __DIR__.'/Busca/web.php';
    require __DIR__.'/Manager/web.php';
    require __DIR__.'/api.php';
});
