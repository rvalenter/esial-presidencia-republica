@extends('Reports.master')

@section('content')
    <div>
        <div class="container mx-auto py-2">
            <div class="mb-10">
                <div class="w-full bg-sky-800 text-white px-4 py-4 border-b-8 border-sky-400">
                    <div class="w-full flex justify-center mb-2">
                        <img class="h-16"
                             src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Coat_of_arms_of_Brazil.svg/1200px-Coat_of_arms_of_Brazil.svg.png">
                    </div>
                    <div class="w-full flex justify-center">
                        <h1 class="text-lg font-semibold">Nota
                            Técnica: {{ \Carbon\Carbon::parse($data['data_referencia'])->format('d/m/Y') }}</h1>
                    </div>
                </div>
                <ul class="mt-1">
                    <li><strong>Enviado por:</strong> {{ $data['users']['name'] }}</li>
                    <li><strong>Orgão:</strong> {{ $data['orgao']['nome'] }}</li>
                </ul>
            </div>
            <div class="mb-10">
                <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                    <div class="h-full"></div>
                    <h2 class="text-lg">
                        Posição: {{ \Illuminate\Support\Str::title($data['posicionamento']['posicionamento']) ?? 'Não Definido' }}</h2>
                </div>
                @if($data['posicao_justificativa'])
                    <ul class="mt-1">
                        <li><strong>Justificativa:</strong> {{ $data['posicao_justificativa'] }}</li>
                    </ul>
                @endif
            </div>
            <div class="mb-10">
                <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                    <div class="h-full border-l-4 border-sky-700"></div>
                    <h2 class="text-lg">Dados da Proposição/ Matéria</h2>
                </div>
                <ul class="mt-1">
                    <li><strong>Sigla:</strong> {{ $data['proposicoes']['sigla']}}</li>
                    <li><strong>Número:</strong> {{ $data['proposicoes']['numero'] }}
                        /{{ $data['proposicoes']['ano'] }}</li>
                    <li><strong>Origem:</strong> {{ $data['proposicoes']['origem'] }}</li>
                    <li><strong>Ementa:</strong> {{ $data['proposicoes']['ementa']['conteudo'] }}</li>
                </ul>
            </div>
            <div class="mb-10">
                <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                    <div class="h-full border-l-4 border-sky-700"></div>
                    <h2 class="text-lg">Status</h2>
                </div>
                <ul class="mt-1">
                    <li><strong>Confidencial:</strong> {{ $data['confidencial'] ? 'Sim' : 'Não' }}</li>
                    <li><strong>Urgente:</strong> {{ $data['urgente'] ? 'Sim' : 'Não'}}</li>
                </ul>
            </div>
            <div class="mb-10">
                <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                    <div class="h-full border-l-4 border-sky-700"></div>
                    <h2 class="text-lg">Referências</h2>
                </div>
                @forelse($data['referenciaRelacaoMany'] as $ref)
                    <ul class="mt-1 flex gap-4 mb-4">
                        <li>-</li>
                        <li>{{ $ref['referencia'] }}</li>
                        <li>{{ $ref['complemento'] ? '- ' . $ref['complemento'] : ''}}</li>
                        <li>{{ $ref['contexto'] ? '- ' . $ref['contexto'] : '' }}</li>
                    </ul>
                @empty
                    <div class="mt-1">
                        <p>Nenhuma referência cadastrada</p>
                    </div>
                @endforelse
            </div>

            <div class="mb-10">
                <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                    <div class="h-full border-l-4 border-sky-700"></div>
                    <h2 class="text-lg">Impacto</h2>
                </div>
                <ul class="mt-1">
                    <li>
                        <strong>Percepção:</strong> {{ is_null($data['impacto_percepcao']) ? '-' : ($data['impacto_percepcao'] ? 'Positivo' : 'Negativo') }}
                    </li>
                    <li>
                        <strong>Intensidade:</strong> {{ $data['impactoIntensidade']['intensidade'] ?? 'Não Definida' }}
                    </li>
                    <li><strong>Tipo:</strong> {{ $data['impactoTipo']['tipo']  ?? 'Não Definido' }}</li>
                </ul>
            </div>
            <div>
                @if($data['meritos']->count() > 0)
                    <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                        <div class="h-full border-l-4 border-sky-700"></div>
                        <h2 class="text-xl">Análises de Méritos</h2>
                    </div>
                    <div class="mb-4 mt-1">
                        @foreach($data['meritos'] as $merito)
                            <div>{!! $merito['conteudo'] !!}</div>
                        @endforeach
                    </div>
                @endif
                @if($data['criticos']->count() > 0)
                    <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                        <div class="h-full border-l-4 border-sky-700"></div>
                        <h2 class="text-xl">Pontos Centrais</h2>
                    </div>
                    <div class="mb-4">
                        @foreach($data['criticos'] as $critico)
                            <div>{!! $critico['conteudo'] !!}</div>
                        @endforeach
                    </div>
                @endif
                @if($data['conclusoes']->count() > 0)
                    <div class="w-full bg-gray-200 text-gray-800 px-2 py-2 border-l-8 {{$border}}">
                        <div class="h-full border-l-4 border-sky-700"></div>
                        @if($data['tipo'] == 0)
                            <h2 class="text-xl">Conclusões</h2>
                        @else
                            <h2 class="text-xl font-semibold">Nota Técnica - SEI</h2>
                        @endif
                    </div>
                    <div class="mb-4">
                        @foreach($data['conclusoes'] as $conclusao)
                            <div>{!! $conclusao['conteudo'] !!}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
