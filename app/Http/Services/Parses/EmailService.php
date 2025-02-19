<?php

namespace App\Http\Services\Parses;

use Illuminate\Support\Str;

class EmailService
{
    public static function hiddenMail(string $mail): string
    {
        $inicio = substr($mail, 0, 3);
        $fim = Str::afterLast($mail, '@');

        return $inicio.str_repeat('*', 7).'@'.$fim;
    }
}
