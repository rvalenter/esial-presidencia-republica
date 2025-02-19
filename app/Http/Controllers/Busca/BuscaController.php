<?php
declare(strict_types=1);

namespace App\Http\Controllers\Busca;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class BuscaController extends Controller
{
    public function show(?string $busca = null): \Inertia\Response
    {
        return Inertia::render('Buscar/Busca', [
            'busca' => $busca,
        ]);
    }
}
