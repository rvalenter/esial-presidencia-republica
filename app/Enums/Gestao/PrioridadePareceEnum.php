<?php

namespace App\Enums\Gestao;

enum PrioridadePareceEnum: int
{
    case IMEDIATO = 1;
    case PRIORITARIO = 2;
    case NORMAL = 3;
    case BAIXO = 4;


    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::IMEDIATO => 'Imediato',
            self::PRIORITARIO => 'Prioritário',
            self::NORMAL => 'Normal',
            self::BAIXO => 'Baixo',
        };
    }
}
