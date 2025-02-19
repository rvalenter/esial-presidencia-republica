<?php

namespace App\Enums\Legislativo;

enum ParlamentarPrioritariasEnum: int
{
    case AGENDA = 1;
    case PRIORITARIA = 2;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::AGENDA => 'Agenda Estratégica União e Reconstrução',
            self::PRIORITARIA => 'Projetos Prioritários dos Ministérios',
        };
    }
}
