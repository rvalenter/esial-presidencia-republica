<?php

namespace App\Models\Relatorio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EsialRelatorioImportacaoAdesao extends Model
{
    use HasFactory;

    protected $fillable = [
        'dsc_partido',
        'dsc_casa',
        'adesao_absoluta',
        'adesao_relativa',
        'adesao_media',
        'adesao_absoluta_crucial',
    ];
}
