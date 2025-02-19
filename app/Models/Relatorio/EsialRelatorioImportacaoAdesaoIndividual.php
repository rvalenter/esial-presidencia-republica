<?php

namespace App\Models\Relatorio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsialRelatorioImportacaoAdesaoIndividual extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigoParlamentar',
        'dsc_casa',
        'adesaoAbsolutaCrucial',
    ];
}
