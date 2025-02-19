<?php

namespace App\Enums\Legislativo;

enum OrigemEnum: int
{
    case CAMARA = 1;
    case SENADO = 2;
    case EXECUTIVO = 3;
    case JUDICIARIO = 4;
    case CONGRESSO = 5;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::CAMARA => 'Câmara dos Deputados',
            self::SENADO => 'Senado Federal',
            self::EXECUTIVO => 'Poder Executivo',
            self::JUDICIARIO => 'Poder Judiciário',
            self::CONGRESSO => 'Congresso Nacional',
        };
    }
}
