<?php
declare(strict_types=1);

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\Manager\PropositionAssessorResource;
use App\Models\Contato\EsialContato;
use App\Models\Usuarios\EsialUsuarioCadastro;
use Illuminate\Http\Request;

class PropositionAssessorController extends Controller
{
    public function __invoke(): PropositionAssessorResource
    {
        return new PropositionAssessorResource(EsialUsuarioCadastro::has('Assessor')->get());
    }
}
