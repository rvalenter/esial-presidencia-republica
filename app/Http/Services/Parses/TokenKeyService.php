<?php

namespace App\Http\Services\Parses;

class TokenKeyService
{
    public static function key(string $arg): string
    {
        return preg_replace('/[^a-zA-Z0-9]/', '', $arg);
    }
}
