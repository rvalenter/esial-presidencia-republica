<?php
declare(strict_types=1);

namespace App\Http\Controllers\Usuarios;

use App\Http\Controllers\Controller;
use App\Http\Resources\Usuarios\CargoResource;
use App\Models\Usuarios\EsialUsuarioCargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    public function show(Request $request): CargoResource
    {
        return new CargoResource(EsialUsuarioCargo::where('nome_cargo', 'iLike', '%'.$request->input('cargo').'%')
            ->take(5)
            ->get());
    }
}
