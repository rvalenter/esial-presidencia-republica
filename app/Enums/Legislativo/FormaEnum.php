<?php

namespace App\Enums\Legislativo;

enum FormaEnum: int
{
    case CONCLUSIVA = 1;
    case NAO_CONCLUSIVA = 2;
    case TERMINATIVA = 3;
    case NAO_TERMINATIVA = 4;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::CONCLUSIVA => 'Conclusiva',
            self::NAO_CONCLUSIVA => 'Não Conclusiva',
            self::TERMINATIVA => 'Terminativa',
            self::NAO_TERMINATIVA => 'Não Terminativa',
        };
    }
}
