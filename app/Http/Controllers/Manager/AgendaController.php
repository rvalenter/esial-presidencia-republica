<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgendaController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Manager/ManagerAgenda');
    }
}
