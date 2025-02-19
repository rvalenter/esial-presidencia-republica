<?php

namespace App\Http\Services\Parses;

class CpfService
{
    public static function sanitize(string $cpf): int
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }
}
