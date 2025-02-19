<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaParametroResource;
use App\Models\Nota\EsialNotaTecnicaImpactoIntensidade;
use App\Models\Nota\EsialNotaTecnicaImpactoTipo;
use App\Models\Nota\EsialNotaTecnicaPosicionamento;
use App\Models\Nota\EsialNotaTecnicaReferenciaPreencimento;

class NotaTecnicaParametroController extends Controller
{
    public function index(): NotaTecnicaParametroResource
    {
        $intensities = EsialNotaTecnicaImpactoIntensidade::all();
        $types = EsialNotaTecnicaImpactoTipo::all();
        $positions = EsialNotaTecnicaPosicionamento::all();
        $references = EsialNotaTecnicaReferenciaPreencimento::all();

        return new NotaTecnicaParametroResource([
            'intensities' => $intensities,
            'types' => $types,
            'positions' => $positions,
            'references' => $references,
        ]);
    }
}
