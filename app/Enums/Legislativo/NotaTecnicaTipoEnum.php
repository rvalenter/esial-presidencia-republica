<?php

namespace App\Enums\Legislativo;

enum NotaTecnicaTipoEnum: int
{
    case TRADICIONAL = 0;
    case SEI = 1;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::TRADICIONAL => 'Tradicional',
            self::SEI => 'SEI',
        };
    }
}
