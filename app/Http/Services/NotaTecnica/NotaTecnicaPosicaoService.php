<?php

namespace App\Http\Services\NotaTecnica;

class NotaTecnicaPosicaoService
{
    public static function border(int $position): string
    {
        return [
            'border-red-500',
            'border-green-500',
            'border-yellow-500',
            'border-blue-500',
            'border-gray-500',
            'border-orange-500',
            'border-teal-500',
            'border-purple-500',
        ][$position - 1] ?? 'border-white';
    }
}
