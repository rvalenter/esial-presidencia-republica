<div
    class="flex items-center justify-between pt-2 pb-2 px-2.5 min-w-32 {{ $classBorder }} border border-white">
    <div class="flex items-center">
        <div class="{{$mobile ? 'h-8 w-8 border' : 'h-16 w-16 border-2'}} bg-[url('../../public/src/img/bg-avatar.jpg')] relative image-container rounded-full bg-cover bg-center bg-no-repeat flex items-center justify-center  border-white">
            <img src="{{$membro->contato->parlamentarDados['url_foto_x']}}"
                 class="absolute inset-0 z-10">
        </div>
        <div class="ml-4">
            <p class="{{$mobile ? 'text-xs' : 'text-sm font-semibold'}}">{{$membro->contato->parlamentarDados['nomeParlamentar']}}</p>
            <p class="{{$mobile ? 'text-[8px]' : 'text-xs'}} text-gray-700">{{$membro->contato->parlamentarDados['siglaPartidoUf']}}</p>
        </div>
    </div>
</div>
