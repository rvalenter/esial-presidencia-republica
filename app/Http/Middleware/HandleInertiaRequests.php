<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Jenssegers\Agent\Agent;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $agent = new Agent();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'profile' => $request->user()->DadosCadastrais->acessoGrupo->nome ?? 'Personalizado',
                'organization' => $request->user()->DadosCadastrais->orgao->nome ?? 'Sem Órgão',
                'isMobile' => $agent->isMobile(),
                'access' => $request->user()?->access->map(function ($item) {
                    return $item->esial_nivel_acesso_id;
                }),
            ],
        ];
    }
}
