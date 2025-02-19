<?php

namespace App\Enums\Legislativo;

enum NotaTecnicaPercepcaoEnum: int
{
    case NEGATIVO = 0;
    case POSITIVO = 1;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::NEGATIVO => 'Negativo',
            self::POSITIVO => 'Positivo',
        };
    }
}
