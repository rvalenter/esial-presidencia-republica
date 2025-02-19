@extends('Reports.master')

@section('content')
    <div>
        <div class="mx-auto px-4">
            <div
                class="{{$isMobile ? 'h-56' : 'h-80'}} bg-[url('https://www.camara.leg.br/tema/assets/images/imagens-compartilhamento/comissoes.jpg')] bg-no-repeat bg-cover pr-8 py-8 custom-rectangle rounded-md">
                <div class="w-full text-right">
                    <h1 class="{{$isMobile ? 'text-sm font-bold' : 'text-xl font-bold'}}  text-white">{{$comissao->sigla}}
                        - {{$comissao->descricao}}</h1>
                    <h2 class="{{$isMobile ? 'text-xs font-light' : 'text-lg font-light'}}  text-white">Comissões
                        Permanentes - {{$comissao->origem}}</h2>
                </div>
            </div>
            <div class="relative -top-56 pl-20 mb-20">
                <div class="w-full flex justify-end mt-5 -mb-14">
                    <a class="text-red-600 relative top-14 z-50"
                       target="_blank"
                       href="{{route('relatorio.comissoes-camara.pdf', ['id' => $comissao->id])}}">
                        <i class="fa-solid fa-file-pdf fa-lg"></i>
                    </a>
                </div>
                <div class="w-full flex items-center gap-8">
                    <div>
                        <div
                            class="{{$isMobile ? 'h-24 w-24 border-2 mt-24' : 'h-48 w-48 border-8'}} border-white bg-sky-700 image-container rounded-full bg-cover bg-center bg-no-repeat">
                            <img class="z-30" src='{{$presidente->contato->parlamentarDados['url_foto_x'] ?? null}}'>
                        </div>
                        <div class="relative -left-20 top-5 border-l-8 pl-2 {{ $presidenteClass ?? null }}">
                            <p class="font-semibold  {{$isMobile ? '' : 'text-2xl'}}">
                                Presidente {{$presidente->contato['nome'] ?? "Não possui."}}</p>
                            <p class="text-sm text-gray-600">{{$presidente->contato->parlamentarDados['siglaPartidoUf'] ?? null}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-12 gap-8 -mt-60 border-t border-gray-300 pt-10">
                <div class="{{$isMobile ? 'col-span-12' : 'col-span-6'}}">
                    <div class="mb-10">
                        <div class="w-full grid grid-cols-3">
                            @forelse($vicePresidente as $vice)
                                <div>
                                    <div
                                        class="{{$isMobile ? 'h-14 w-14 border-2' : 'h-20 w-20 border-4'}} bg-sky-700 image-container rounded-full bg-cover bg-center bg-no-repeat  border-gray-300">
                                        <img class="z-30" src='{{  $vice['foto'] }}'>
                                    </div>
                                    <div class="border-l-8 pl-2 {{  $vice['ringClass'] }}">
                                        <p class="{{$isMobile ? 'text-xs' : 'text-sm'}} mt-2 mb-1 text-gray-700  font-semibold">{{$vice['nome']}}</p>
                                        <p class="{{$isMobile ? 'text-[10px]' : 'text-xs'}}  mb-1 text-gray-700">{{$vice['partido']}}</p>
                                        <p class="{{$isMobile ? 'text-[10px]' : 'text-xs'}}  mb-1 text-gray-600">{{$vice['cargo']}}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-3">
                                    <p>Não há vice-presidentes.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @if($isManager)
                    <div class="{{$isMobile ? 'col-span-12' : 'col-span-6'}} relative -top-5">
                        <div class="bg-gray-200 rounded-2xl p-4">
                            <h3 class="text-lg font-semibold mb-4">Estatísticas</h3>
                            <div class="mb-10">
                                <h3 class="mb-2 font-semibold">Classificação por Alinhamento Partidário</h3>
                                <div class="w-full bg-red-500 rounded-full h-2.5">
                                    <div class="bg-green-500 h-2.5 rounded-full"
                                         style="width: {{($countByAlinhamento['BG']['percentage'] ?? 0) + ($countByAlinhamento['AC']['percentage'] ?? 0)}}%"></div>
                                </div>
                                <div class="flex items-center mt-2">
                                    <div class="mr-4">
                                        <div class="flex items-center gap-2 text-gray-600 text-[9px] font-semibold">
                                            <div class="w-3 h-3 rounded-full bg-green-600"></div>
                                            Base: {{($countByAlinhamento['BG']['percentage'] ?? 0) + ($countByAlinhamento['AC']['percentage'] ?? 0) }}
                                            % -
                                            Integrantes: {{($countByAlinhamento['BG']['amount'] ?? 0) + ($countByAlinhamento['AC']['amount'] ?? 0) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 text-gray-600 text-[9px] font-semibold">
                                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                            Oposição: {{($countByAlinhamento['OP']['percentage'] ?? 0) + ($countByAlinhamento['DS']['percentage'] ?? 0)}}
                                            % -
                                            Integrantes: {{($countByAlinhamento['OP']['amount'] ?? 0) + ($countByAlinhamento['DS']['amount'] ?? 0) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10">
                                <h3 class="mb-2 font-semibold">Titulares</h3>

                                <div class="w-full bg-red-500 rounded-full h-2.5 flex">
                                    <div class="bg-green-600 h-2.5 rounded-l-full"
                                         style="width: {{$countByAlinhamento['BG']['percentage'] ?? 0}}%"></div>
                                    <div class="bg-lime-400 h-2.5"
                                         style="width: {{$countByAlinhamento['AC']['percentage'] ?? 0}}%"></div>
                                    <div class="bg-yellow-400 h-2.5 rounded-r-full"
                                         style="width: {{$countByAlinhamento['DS']['percentage'] ?? 0}}%"></div>
                                </div>
                                <div class="flex gap-4 mb-1 mt-2">
                                    <div class="flex items-center gap-2 text-gray-600 text-[9px] font-semibold">
                                        <div class="w-3 h-3 rounded-full bg-green-600"></div>
                                        Base do Governo: {{$countByAlinhamento['BG']['percentage'] ?? 0}}%
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600 text-[9px] font-semibold">
                                        <div class="w-3 h-3 rounded-full bg-lime-400"></div>
                                        Apoio Condicional: {{$countByAlinhamento['AC']['percentage'] ?? 0}}%
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600 text-[9px] font-semibold">
                                        <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                        Oposição Em Disputa: {{$countByAlinhamento['DS']['percentage'] ?? 0}}%
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600 text-[9px] font-semibold">
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        Oposição: {{$countByAlinhamento['OP']['percentage'] ?? 0}}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="mb-10">
                <h3 class="text-lg font-semibold mb-4">Membros:</h3>
                <div class="grid grid-cols-4">
                    @php $counter = 0; @endphp

                    @foreach($membros->where('alinhamento', 'BG') as $membro)
                        <x-reports.comissao-membro-component
                            :membro="$membro"
                            :mobile="$isMobile"
                            classBorder="{{$isManager ? 'bg-green-400' : 'bg-gray-200'}}" />
                        @php $counter++; @endphp
                        @if($counter % 32 == 0)
                </div>
                @pageBreak
                <div class="grid grid-cols-4">
                    @endif
                    @endforeach

                    @foreach($membros->where('alinhamento', 'AC') as $membro)
                        <x-reports.comissao-membro-component
                            :membro="$membro"
                            :mobile="$isMobile"
                            classBorder="{{$isManager ? 'bg-lime-400' : 'bg-gray-200'}}" />
                        @php $counter++; @endphp
                        @if($counter % 32 == 0)
                </div>
                @pageBreak
                <div class="grid grid-cols-4">
                    @endif
                    @endforeach

                    @foreach($membros->where('alinhamento', 'DS') as $membro)
                        <x-reports.comissao-membro-component
                            :membro="$membro"
                            :mobile="$isMobile"
                            classBorder="{{$isManager ? 'bg-yellow-500' : 'bg-gray-200'}}" />
                        @php $counter++; @endphp
                        @if($counter % 32 == 0)
                </div>
                @pageBreak
                <div class="grid grid-cols-4">
                    @endif
                    @endforeach

                    @foreach($membros->where('alinhamento', 'OP') as $membro)
                        <x-reports.comissao-membro-component
                            :membro="$membro"
                            :mobile="$isMobile"
                            classBorder="{{$isManager ? 'bg-red-400' : 'bg-gray-200'}}" />
                        @php $counter++; @endphp
                        @if($counter % 32 == 0)
                </div>
                @pageBreak
                <div class="grid grid-cols-4">
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .image-container {
            overflow: hidden;
            position: relative;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .custom-rectangle {
            clip-path: polygon(0 0, 300% 0%, 0% 70%);

        }
    </style>
@endsection
