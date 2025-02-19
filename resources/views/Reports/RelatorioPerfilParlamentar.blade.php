@extends('Reports.master')

@section('content')
    <div style="page-break-after:auto;">
        <div class="flex justify-center w-full">
            <div id="printableDiv" class="w-full p-4">
                <div class="w-full text-right absolute right-10 top-10">
                    <p class="text-2xl text-white">Perfil Parlamentar</p>
                    <p class="text-white text-sm">{{$contato->cargo === 'Deputado Federal' ? 'Camara Dos Deputados' : 'Senado Federal'}}</p>
                </div>
                <div
                    class="w-full h-60 {{$contato->cargo === 'Deputado Federal'
                        ? "bg-[url('https://static.poder360.com.br/2021/12/camara-deputados-848x477.jpg')]"
                        : "bg-[url('https://upload.wikimedia.org/wikipedia/commons/4/47/Plen%C3%A1rio_do_Senado_%2843010124995%29.jpg')]"  }}
                        bg-cover bg-center bg-no-repeat rounded-3xl flex pt-24 pl-36">
                    <div
                        class="bg-sky-700 relative top-1 left-2.5 image-container rounded-full h-44 w-44 ring-8 ring-white bg-cover bg-center bg-no-repeat flex items-center justify-center">
                        <img class="z-30 " src='{{$contato->parlamentarDados->url_foto_x}}'>
                    </div>
                    <div class="h-full justify-between flex items-end relative top-5 left-5">
                        <p class="text-2xl font-semibold relative top-12 text-gray-600">
                            {{\Illuminate\Support\Str::title($contato->parlamentarDados->parlamentar_completo_x)}}
                            <br>
                            <span class="text-sm font-light relative -top-2">
                                {{$contato->parlamentarDados->siglaPartidoUf}}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="pt-16 w-full flex justify-between items-end pb-0.5">
                    <a
                        class="text-red-600"
                        target="_blank"
                        href="{{route('relatorio.perfil-parlamentar.pdf', ['id' => $contato->id])}}">
                        <i class="fa-solid fa-file-pdf fa-lg"></i>
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-8 w-full pt-4 h-40 border-t border-gray-400">
                    <div class="bg-neutral-200/50 border border-400  w-full h-full col-span-1">
                        <div
                            class="bg-neutral-300 text-gray-500 h-8 px-2 flex items-center border-b-2 border-sky-600">
                            <div class="h-1/3 border-l-4 border-sky-500 mr-2"></div>
                            <p class=" text-sm">Avaliação Política</p>
                        </div>
                        <div class="w-full h-full -mt-8 pt-8">
                            <div class="w-full h-full flex justify-center items-center">
                                <p class="text-lg font-semibold" id="avaliacao">
                                    {{$contato->parlamentarDados->avaliacao}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-neutral-200/50 border border-gray-200 w-full h-full col-span-2">
                        <div
                            class="bg-neutral-300 text-gray-500 h-8 px-2 flex items-center border-b-2 border-sky-600">
                            <div class="h-1/3 border-l-4 border-sky-500 mr-2"></div>
                            <p class=" text-sm">Estatíticas do Parlamentar</p>
                        </div>
                        <div class="w-full h-full -mt-8 pt-8 ">
                            <div class="px-4 py-2 flex h-full">
                                <div class="flex justify-around items-center border-r border-gray-400 w-6/12 ">
                                    <div class="text-center">
                                        <p class="text-3xl font-semibold text-green-500 ">
                                            {{$contato->parlamentarDados->a_favor}}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1 ">Votos<br>Favoráveis</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-3xl font-semibold text-red-500 ">
                                            {{$contato->parlamentarDados->contra}}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1 ">Votos<br>Contrários</p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-around w-full">
                                    <div class="text-center">
                                        <p class="text-4xl text-gray-600 ml-1">{{(int)$contato->parlamentarDados->relativa}}
                                            <span class="text-sm">%</span></p>
                                        <p class="text-xs text-gray-500 mt-1">Adesão<br>Relativa</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-4xl text-gray-600 ml-1">{{(int)$contato->parlamentarDados->absoluta}}
                                            <span class="text-sm">%</span></p>
                                        <p class="text-xs text-gray-500 mt-1">Adesão<br>Absoluta</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-4xl text-gray-600 ml-1">{{(int)$contato->parlamentarDados->percentual_pesos}}
                                            <span class="text-sm">%</span></p>
                                        <p class="text-xs text-gray-500 mt-1">Adesão<br>Média</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-4xl text-gray-600 ml-1">{{(int)$contato->parlamentarDados->ausencia}}
                                            <span class="text-sm">%</span></p>
                                        <p class="text-xs text-gray-500 mt-1">Ausências</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full h-fit mt-8  border border-gray-300">
                    <div class="bg-neutral-300 h-8 px-2 flex items-center border-b-2 border-sky-600">
                        <div class="h-1/3 border-l-4 border-sky-500 mr-2"></div>
                        <p class="text-gray-600 text-sm">Cargos Liderança:</p>
                    </div>
                    <div class="py-3 px-8 bg-neutral-200/50 min-h-10">
                        <ul class="list-disc list-outside">
                            @foreach($contato->parlamentarDados->cargo_lideranca_y as $destaque)
                                @if($destaque != ' ')
                                    <li class="text-gray-600 text-sm font-bold mb-2">
                                        {{\Illuminate\Support\Str::remove("- ", $destaque)}};
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="w-full h-fit mt-8 border border-gray-300">
                    <div class="bg-neutral-300 h-8 px-2 flex items-center border-b-2 border-sky-600">
                        <div class="h-1/3 border-l-4 border-sky-500 mr-2"></div>
                        <p class="text-gray-600 text-sm">Contatos:</p>
                    </div>
                    <div class="py-3 px-8 bg-neutral-200/50 min-h-24">
                        <ul class="list-disc list-outside">
                            @if($contato->parlamentarDados->tel_gabinete != null)
                                <li class="text-gray-600 text-sm mb-2">
                                    Telefone: {{$contato->parlamentarDados->tel_gabinete}}</li>
                            @endif
                            @if($contato->parlamentarDados->tel_chefe_gab != null)
                                <li class="text-gray-600 text-sm mb-2">Telefone -
                                    Gabinete: {{$contato->parlamentarDados->tel_chefe_gab}}</li>
                            @endif
                            @if($contato->parlamentarDados->tel_particular != null)
                                <li class="text-gray-600 text-sm mb-2">
                                    Celular: {{$contato->parlamentarDados->tel_particular}}</li>
                            @endif
                            @if($contato->parlamentarDados->tel_chefe_gab != null)
                                <li class="text-gray-600 text-sm mb-2">Celular - Chefe
                                    Gabinete: {{$contato->parlamentarDados->tel_chefe_gab}}
                                    ({{\Illuminate\Support\Str::title($contato->parlamentarDados->chefe_gab)}})
                                </li>
                            @endif
                            @if($contato->parlamentarDados->email_x != null)
                                <li class="text-gray-600 text-sm mb-2">
                                    Email: {{$contato->parlamentarDados->email_x}}</li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="w-full h-fit mt-8">
                    <div class="bg-neutral-300 h-8 px-2 flex items-center border-b-2 border-sky-600">
                        <div class="h-1/3 border-l-4 border-sky-500 mr-2"></div>
                        <p class="text-gray-600 text-sm">Histórico de Votações cruciais:</p>
                    </div>
                    @forelse($contato->parlamentarDados->votacoes as $votacao)
                        <div
                            class="w-full flex cursor-pointer group hover:bg-gray-200/60 group px-4 bg-neutral-200 min-h-24">
                            <div class="col-span-1 flex justify-center items-center relative pl-5">
                                <div class="h-full border-l-2 border-neutral-400/80 absolute"></div>
                                {{--                                <div--}}
                                {{--                                    class="h-3.5 w-3.5 group-hover:scale-125 rounded-full absolute text-white {{$votacao->dsc_voto['bg1']}}">--}}
                                {{--                                </div>--}}
                                <div
                                    class="h-2 w-2 group-hover:scale-125 rounded-full absolute flex justify-center items-center ring-8 ring-neutral-200 group-hover:ring-gray-200/60 {{$votacao->dsc_voto['bg2']}}">
                                </div>
                            </div>
                            <div class="w-full grid grid-cols-12 col-span-11 py-6 text-sm text-gray-600">
                                <div class="col-span-2 h-full flex items-center justify-center">
                                    <p>{{$votacao->data_votacao}}</p>
                                </div>
                                <div class="col-span-8">
                                    <p class="mb-1">{{$votacao->dsc_votacao}}.</p>
                                    <p class="mb-1">Apelido: {{$votacao->dsc_apelido}};</p>
                                </div>
                                <div class="col-span-2 flex items-center justify-center">
                                    <p>{{$votacao->dsc_voto['resultado']}}</p>
                                </div>
                            </div>
                        </div>
                        @if (($loop->index - 3) % 13 == 0)
                            @pageBreak
                        @endif
                    @empty
                        <div class="py-3 px-8 bg-neutral-200/50 min-h-24">
                            <p class="text-gray-600 text-sm">Nenhuma votação importante registrada.</p>
                        </div>
                    @endforelse
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
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const avaliacaoElement = document.getElementById("avaliacao");
            const avaliacaoTexto = avaliacaoElement.textContent.trim();

            switch (avaliacaoTexto) {
                case 'Base Governo - BG':
                    avaliacaoElement.style.color = '#22c55e';
                    break;
                case 'Oposição - OP':
                    avaliacaoElement.style.color = '#ef4444';
                    break;
                case 'Apoio Condicional - AC':
                    avaliacaoElement.style.color = '#84cc16';
                    break;
                default:
                    avaliacaoElement.style.color = '#eab308';
            }
        });
    </script>
@endsection
