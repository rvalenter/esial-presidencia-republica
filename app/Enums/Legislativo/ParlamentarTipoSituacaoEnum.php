<?php

namespace App\Enums\Legislativo;

enum ParlamentarTipoSituacaoEnum: int
{
    case TITULAR = 1;
    case SUPLENTE = 2;
    case EFETIVADO = 3;
    case DADOS_LEGAODS = 4;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::TITULAR => 'Titular',
            self::SUPLENTE => 'Suplente',
            self::EFETIVADO => 'Efetivado',
            self::DADOS_LEGAODS => 'Dados Legados',
        };
    }
}
