<?php

namespace App\Http\Services\Https;

use Illuminate\Support\Facades\URL;

class Verification
{
    public static function setHttps(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}
