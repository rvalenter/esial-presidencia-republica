<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpFoundation\Request as RequestAlias;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ])->statefulApi();
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
            'Excel' => Maatwebsite\Excel\Facades\Excel::class,
            'Agent' => Jenssegers\Agent\Facades\Agent::class,
        ]);
        $middleware->trustProxies(at: '*');
        $middleware->trustProxies(headers: RequestAlias::HEADER_X_FORWARDED_FOR |
            RequestAlias::HEADER_X_FORWARDED_HOST |
            RequestAlias::HEADER_X_FORWARDED_PORT |
            RequestAlias::HEADER_X_FORWARDED_PROTO |
            RequestAlias::HEADER_X_FORWARDED_AWS_ELB
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
