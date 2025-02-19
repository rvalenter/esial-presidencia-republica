<?php
declare(strict_types=1);

namespace App\Http\Controllers\Posicionamento;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class PosicionamentoController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Posicionamento/Posicionamento');
    }
}
