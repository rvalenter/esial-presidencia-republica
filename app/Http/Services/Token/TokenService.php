<?php

namespace App\Http\Services\Token;

use App\Http\Services\Parses\TokenKeyService;
use App\Mail\Login\Verification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class TokenService
{
    public static function generator(string $arg): int
    {
        $token = str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        $tokenKey = 'Token_'.TokenKeyService::key($arg);

        if (Cache::has($tokenKey)) {
            Cache::forget($tokenKey);
        }

        Cache::put($tokenKey, Crypt::encrypt($token), now()->addMinutes(5));

        return $token;
    }

    public static function validator(string $arg, ?string $token = null, ?int $tries = null): bool
    {
        $tokenKey = 'Token_'.TokenKeyService::key($arg);

        if (! Cache::has($tokenKey) || $token === null) {
            return false;
        }

        return $token === Crypt::decrypt(Cache::get($tokenKey));
    }

    public static function sendToken(string $email): void
    {
        Mail::to($email)->send(new Verification(TokenService::generator($email)));
    }
}
