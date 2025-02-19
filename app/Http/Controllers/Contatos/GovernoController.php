<?php
declare(strict_types=1);

namespace App\Http\Controllers\Contatos;

use App\Http\Controllers\Controller;
use App\Http\Resources\Contatos\GovernoResource;
use App\Models\Contato\EsialContato;
use App\Models\Relatorio\EsialRelatorioImportacaoBaseParlamentar;

class GovernoController extends Controller
{
    public function index(int $casa, string $partido): GovernoResource
    {
        return new GovernoResource(
            EsialContato::with(['parlamentarDados:cod_parlamentar,avaliacao,url_foto_x,nomeParlamentar,siglaPartidoUf,avaliacao,percentual_pesos'])
                ->when($casa == 1, fn ($query) => $query->where('cargo', 'Deputado Federal'))
                ->when($casa == 2, fn ($query) => $query->where('cargo', 'Senador Federal'))
                ->when($casa == 4 && $partido != 'todos', fn ($query) => $query->whereHas('parlamentarDados', fn ($q) => $q->where('siglaPartido', $partido)))
                ->get()
                ->groupBy('parlamentarDados.avaliacao')
        );
    }

    public function listPartidos(): GovernoResource
    {
        return new GovernoResource(
            EsialRelatorioImportacaoBaseParlamentar::select('siglaPartido')
                ->distinct()
                ->orderBy('siglaPartido')
                ->get()
        );
    }
}
