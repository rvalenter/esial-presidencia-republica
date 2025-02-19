<?php

namespace App\Models\Relatorio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EsialRelatorioImportacaoVotacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'dsc_votacao',
        'data_votacao',
        'hora_votacao',
        'dsc_voto',
        'dsc_orientacao_partido',
        'dsc_orientacao_governo',
        'dsc_peso',
        'dsc_casa',
        'dsc_apelido',
        'cod_parlamentar',
        'cod_votacao',
    ];

    public function getDscVotoAttribute($value)
    {
        if ($value == 'A FAVOR GOV.') {
            return [
                'resultado' => 'Favorável Gov.',
                'bg1' => 'bg-green-200',
                'bg2' => 'bg-green-600 group-hover:bg-green-700',
                'bg3' => 'bg-green-100',
            ];
        }

        if ($value == 'CONTRA GOV.') {
            return [
                'resultado' => 'Contrário Gov.',
                'bg1' => 'bg-red-200',
                'bg2' => 'bg-red-600 group-hover:bg-red-700',
                'bg3' => 'bg-red-100',
            ];
        }

        if ($value == 'OBSTRUÇÃO') {
            return [
                'resultado' => 'Obstrução',
                'bg1' => 'bg-yellow-200',
                'bg2' => 'bg-yellow-600 group-hover:bg-yellow-700',
                'bg3' => 'bg-yellow-100',
            ];
        }

        return [
            'resultado' => Str::title($value),
            'bg1' => 'bg-gray-300',
            'bg2' => 'bg-gray-400 group-hover:bg-gray-700',
            'bg3' => 'bg-gray-100',
        ];
    }

    public function getDataVotacaoAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }
}
