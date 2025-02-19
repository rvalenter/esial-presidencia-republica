<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaAreaTematicaResource;
use App\Models\Nota\EsialNotaTecnicaAreaTematica;
use App\Models\Nota\EsialNotaTecnicaAreaTematicaRelacionamento;
use Illuminate\Http\Request;

class NotaTecnicaAreaTematicaController extends Controller
{
    public function show(Request $request): NotaTecnicaAreaTematicaResource
    {
        return new NotaTecnicaAreaTematicaResource(
            EsialNotaTecnicaAreaTematica::where('area_tematica', 'like', "%{$request->text}%")
                ->take(3)
                ->get()
        );
    }

    public function store(Request $request):void
    {
        $at = EsialNotaTecnicaAreaTematica::updateOrCreate([
            'area_tematica' => $request->areaTematica,
        ]);

        EsialNotaTecnicaAreaTematicaRelacionamento::updateOrCreate([
            'esial_nota_tecnica_id' => $request->ntId,
            'esial_nota_tecnica_area_tematica_id' => $at->id,
        ], [
            'user_id' => auth()->id(),
        ]);
    }

    public function destroy(Request $request): void
    {
        EsialNotaTecnicaAreaTematicaRelacionamento::where([
            'esial_nota_tecnica_area_tematica_id' => $request->id,
            'esial_nota_tecnica_id' => $request->laravel_through_key,
        ])->delete();
    }
}
