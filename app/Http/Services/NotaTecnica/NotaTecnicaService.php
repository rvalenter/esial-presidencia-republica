<?php

namespace App\Http\Services\NotaTecnica;

use App\Models\Legislativo\EsialLegislativoProposicao;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NotaTecnicaService
{
    public static function cache(Request $request)
    {
        return self::search($request);
    }

    public static function updateCache(Request $request)
    {
        $user = Auth::user()->DadosCadastrais;

        if (
            $request->input('page') != "1" ||
            ($request->filled('search') && !in_array(self::parse($request->input('search')), (array)Cache::get('searchTerms', []))) ||
            $request->input('filter') != "todas" ||
            !in_array($user->esial_usuario_orgao_id, (array)Cache::get('organizations', [])) ||
            !in_array($user->id, (array)Cache::get('users', []))
        ) {
            $notes = collect(Cache::get('notes'))->merge(self::search($request));

            Cache::put('organizations', $user->esial_usuario_orgao_id);
            Cache::put('users', $user->id);
            Cache::put('searchTerms', self::parse($request->input('search')));
            Cache::put('notes', $notes);

            return self::filterCache($notes, $request);
        }

        return self::filterCache(Cache::get('notes'), $request);
    }

    public static function filterCache(Collection $cache, Request $request)
    {
        $search = Str::upper($request->input('search'));
        $user = Auth::user()->DadosCadastrais;

        return $cache->filter(function ($note) use ($search, $user, $request) {
            $return = $note->notaTecnica
                ->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id)
                ->isNotEmpty();

            if ($search) {
                $return = Str::contains($note->sigla . $note->numero . $note->ano, $search);
            }

            if ($request->input('filter') === 'sri') {
                $return = $note->notaTecnica
                    ->where('codigo_parecer', 'LIKE', 's%')
                    ->where('concluida', false)
                    ->isNotEmpty();
            }

            if ($request->input('filter') === 'iniciadas') {
                $return = $note->notaTecnica
                    ->where('concluida', false)
                    ->where('user_id', $user->id)
                    ->isNotEmpty();
            }

            if ($request->input('filter') === 'minhas') {
                $return = $note->notaTecnica
                    ->where('user_id', $user->id)
                    ->isNotEmpty();
            }

            if ($request->input('filter') === 'concluidas') {
                $return = $note->notaTecnica
                    ->where('concluida', true)
                    ->isNotEmpty();
            }

            if ($request->input('filter') === 'avaliar') {
                $return = $note->notaTecnica
                    ->where('concluida', false)
                    ->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id)
                    ->whereHas('aprovacoes', fn($query) => $query->whereNull('observacao'))
                    ->isNotEmpty();
            }

            if ($request->input('filter') === 'prioritarias') {
                $return = $note->prioritarias
                    ->where('tipo', 2)
                    ->isNotEmpty();
            }

            if ($request->input('filter') === 'agenda') {
                $return = $note->prioritarias
                    ->where('tipo', 1)
                    ->isNotEmpty();
            }

            return $return;
        })->unique('id');
    }

    public static function paginate($items, $perPage = 30, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public static function search(Request $request): mixed
    {
        $user = Auth::user()->DadosCadastrais;
        $search = self::parse($request->input('search'));

        return EsialLegislativoProposicao::with([
            'parlamentares.contato',
            'ementa',
            'notaTecnica.referencias',
            'notaTecnica' => fn($query) => $query->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id),
        ])
            ->whereIn('sigla', self::acronym())
            ->when($request->input('isId') == 'true', fn($query) => $query->where('id', $search))
            ->when($request->filled('search') && $request->isId == 'false', fn($query) => $query->whereRaw(DB::raw('(sigla || numero || ano) ILIKE ?'), ["%{$search}%"]))
            ->when($request->input('filter') !== 'todas', function ($query) use ($request, $user, $search) {
                $query
                    ->when($request->input('filter') === 'sri', fn($query) => $query->whereRaw(DB::raw('(sigla || numero || ano) ILIKE ?'), ["%{$search}%"])
                        ->whereHas('notaTecnica', fn($query) => $query->where('codigo_parecer', 'LIKE', 's%')
                            ->where('concluida', false)
                            ->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id)))
                    ->when($request->input('filter') === 'iniciadas', fn($query) => $query->whereHas('notaTecnica', fn($query) => $query->where('concluida', false)
                        ->where('user_id', $user->id)
                        ->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id)))
                    ->when($request->input('filter') === 'minhas', fn($query) => $query->whereHas('notaTecnica', fn($query) => $query->where('user_id', $user->id)
                        ->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id)))
                    ->when($request->input('filter') === 'concluidas', fn($query) => $query->whereHas('notaTecnica', fn($query) => $query->where('concluida', true)
                        ->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id)))
                    ->when($request->input('filter') === 'avaliar', fn($query) => $query->whereHas('notaTecnica', fn($query) => $query->where('concluida', false)
                        ->where('esial_usuario_orgao_id', $user->esial_usuario_orgao_id))
                        ->whereHas('notaTecnica.aprovacoes', fn($query) => $query->whereNull('observacao')))
                    ->when($request->input('filter') === 'prioritarias', fn($query) => $query->whereIn(DB::raw('(sigla || numero || ano)'), fn($query) => $query->select('descricao')
                        ->from('esial_legislativo_proposicao_prioritarias')
                        ->where('tipo', 2)))
                    ->when($request->input('filter') === 'agenda', fn($query) => $query->whereIn(DB::raw('(sigla || numero || ano)'), fn($query) => $query->select('descricao')
                        ->from('esial_legislativo_proposicao_prioritarias')
                        ->where('tipo', 1)));
            })
            ->orderBy('ano', 'desc')
            ->paginate(20);
    }

    public static function parse($search): string
    {
        return preg_replace('/[^A-Za-z0-9\-]/', '', $search);
    }

    public static function acronym(): array
    {
        return [
            'INC',
            'INS',
            'MPV',
            'MSC',
            'MSF',
            'MSG',
            'PDC',
            'PDL',
            'PDN',
            'PDS',
            'PEC',
            'PL',
            'PLC',
            'PLN',
            'PLP',
            'PLS',
            'PLV',
            'PRC',
            'REQ',
            'RIC',
            'RQN',
            'RQS',
            'TVR',
            'VET'
        ];
    }
}
