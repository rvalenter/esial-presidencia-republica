<?php

namespace App\Http\Services\Parses;

class TextService
{
    public static function parse($text): string
    {
        return trim(mb_convert_encoding($text, 'UTF-8', 'auto'));
    }
}
