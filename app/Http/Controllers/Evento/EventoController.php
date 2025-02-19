<?php
declare(strict_types=1);

namespace App\Http\Controllers\Evento;

use App\Http\Controllers\Controller;
use App\Http\Resources\Evento\EventoResource;
use App\Http\Services\Notification\Notification;
use App\Models\Eventos\EsialEvento;
use App\Models\Legislativo\EsialLegislativoColegiadoReuniao;
use App\Models\Legislativo\EsialLegislativoProposicao;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function autocomplete(string $user): EventoResource
    {
        return new EventoResource(
            User::query()
                ->where(fn ($query) => $query->where('name', 'like', "%$user%")
                    ->orWhere('email', 'like', "%$user%"))
                ->take(5)
                ->get() ?? []
        );
    }

    public function legislativoAutocomplete(Request $request): EventoResource
    {
        $proposicao = preg_replace('/[^A-Za-z0-9\-]/', '', $request->proposicao);

        return new EventoResource(
            EsialLegislativoProposicao::query()
                ->where(fn ($query) => $query->whereRaw(DB::raw("(sigla || numero || ano) like '%".$proposicao."%'")))
                ->take(5)
                ->get() ?? []
        );
    }

    public function store(Request $request): void
    {
        $author = Auth::id();

        $event = EsialEvento::create([
            'titulo' => $request->title,
            'descricao' => $request->description,
            'data_inicio' => $request->start,
            'data_fim' => $request->end,
            'user_id' => $author,
        ]);

        $event->users()->attach(collect($request->users)->pluck('id'));
        $event->propositions()->attach(collect($request->legislatives)->pluck('id'));

        if ($request->collegiate) {
            $event->colegiados()->attach($request->collegiate);
        }

        collect($request->users)->each(fn ($user) => Notification::create($user['id'], 'Você foi incluído em um novo evento: '.$request->title));
    }

    public function update(Request $request): void
    {
        if ($request->id) {
            $event = EsialEvento::find($request->id);

            $event->update([
                'titulo' => $request->title,
                'descricao' => $request->description,
                'data_inicio' => $request->start,
                'data_fim' => $request->end,
            ]);

            $event->users()->sync(collect($request->users)->pluck('id'));
            $event->propositions()->sync(collect($request->legislatives)->pluck('id'));
            $event->colegiados()->sync($request->collegiate);

            collect($request->users)->each(fn ($user) => Notification::create($user['id'], 'O evento: '.$request->title.' foi atualizado!'));
        }
    }

    public function destroy(Request $request): void
    {
        $event = EsialEvento::find($request->id);

        if ($request->isOwner) {
            $event->delete();
            $event->guest->each(fn ($guest) => Notification::create($guest->user_id, 'Evento cancelado: '.$event->titulo));
        } else {
            $event->users()->detach(Auth::id());
            Notification::create($event->user_id, 'O usuário, '.Auth::user()->name.', não participará do evento: '.$event->titulo);
        }
    }

    public function index(): EventoResource
    {
        $user = Auth::id();

        $myEvents = EsialEvento::where('user_id', $user)
            ->whereYear('data_inicio', '>=', Carbon::now()->subYear()->year)
            ->with('guest')
            ->get();

        $guestEvents = EsialEvento::whereHas('guest', fn ($query) => $query->where('user_id', $user))
            ->where('user_id', '!=', $user)
            ->whereYear('data_inicio', '>=', Carbon::now()->subYear()->year)
            ->with('guest')
            ->get();

        return new EventoResource([
            'myEvents' => $myEvents,
            'guestEvents' => $guestEvents,
        ]);
    }

    public function show(int $id): EventoResource
    {
        return new EventoResource(EsialEvento::find($id)->load('guestData', 'propositionsData', 'colegiadosData.colegiado'));
    }

    public function listCollegiate(Request $request): EventoResource
    {
        return new EventoResource(EsialLegislativoColegiadoReuniao::query()
            ->whereBetween('data', [Carbon::parse($request->range['start']), Carbon::parse($request->range['end'])])
            ->with('colegiado')
            ->get());
    }
}
