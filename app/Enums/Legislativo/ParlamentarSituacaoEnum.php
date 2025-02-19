<?php

namespace App\Enums\Legislativo;

enum ParlamentarSituacaoEnum: int
{
    case EXECICIO = 1;
    case EX_PARLAMENTAR = 2;
    case LICENCIADO = 3;
    case AFASTADO = 4;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::EXECICIO => 'Em exercício',
            self::EX_PARLAMENTAR => 'Ex-parlamentar',
            self::LICENCIADO => 'Licenciado',
            self::AFASTADO => 'Afastado',
        };
    }
}
