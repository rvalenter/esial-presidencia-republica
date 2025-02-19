<?php
declare(strict_types=1);

namespace App\Enums\Legislativo;

enum RegimeEnum: int
{
    case PRIORIDADE = 1;
    case ORDINÁRIA = 2;
    case ESPECIAL = 3;
    case URGÊNCIA = 4;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::ESPECIAL => 'Especial',
            self::ORDINÁRIA => 'Ordinária',
            self::PRIORIDADE => 'Prioridade',
            self::URGÊNCIA => 'Urgência',
        };
    }
}
