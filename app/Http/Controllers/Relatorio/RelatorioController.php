<?php
declare(strict_types=1);

namespace App\Http\Controllers\Relatorio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Relatorio\ImportacaoRequest;
use App\Imports\Relatorio\EsialRelatorioImportacaoAdesaoImport;
use App\Imports\Relatorio\EsialRelatorioImportacaoAdesaoIndividualImport;
use App\Imports\Relatorio\EsialRelatorioImportacaoBaseParlamentarImport;
use App\Imports\Relatorio\EsialRelatorioImportacaoComissaoImport;
use App\Imports\Relatorio\EsialRelatorioImportacaoProposicaoImport;
use App\Imports\Relatorio\EsialRelatorioImportacaoVotacaoImport;
use App\Models\Legislativo\EsialLegislativoColegiado;
use App\Models\Relatorio\EsialRelatorioImportacaoAdesao;
use App\Models\Relatorio\EsialRelatorioImportacaoAdesaoIndividual;
use App\Models\Relatorio\EsialRelatorioImportacaoBaseParlamentar;
use App\Models\Relatorio\EsialRelatorioImportacaoComissao;
use App\Models\Relatorio\EsialRelatorioImportacaoVotacao;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioController extends Controller
{
    public function index(): \Inertia\Response
    {
        return Inertia::render('Relatorio/Relatorio');
    }

    public function store(ImportacaoRequest $request): void
    {
        $type = $request['type'];
        $csv = $request['csv'];

        switch ($type) {
            case 1:
                EsialRelatorioImportacaoAdesao::truncate();
                Excel::import(new EsialRelatorioImportacaoAdesaoImport, $csv);
                break;
            case 2:
                EsialRelatorioImportacaoAdesaoIndividual::truncate();
                Excel::import(new EsialRelatorioImportacaoAdesaoIndividualImport, $csv);
                break;
            case 3:
                EsialRelatorioImportacaoVotacao::truncate();
                Excel::import(new EsialRelatorioImportacaoVotacaoImport, $csv);
                break;
            case 4:
                EsialRelatorioImportacaoBaseParlamentar::truncate();
                Excel::import(new EsialRelatorioImportacaoBaseParlamentarImport, $csv);
                break;
            case 5:
                EsialRelatorioImportacaoComissao::truncate();
                EsialLegislativoColegiado::truncate();
                Excel::import(new EsialRelatorioImportacaoComissaoImport, $csv);
                break;
            case 6:
                Excel::import(new EsialRelatorioImportacaoProposicaoImport, $csv);
                break;
        }
    }
}
