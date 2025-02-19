<?php
declare(strict_types=1);

namespace App\Http\Controllers\Notas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Notas\NotaTecnicaArquivoResource;
use App\Models\Nota\EsialNotaTecnicaAnexo;
use Illuminate\Http\Request;

class NotaTecnicaArquivoController extends Controller
{
    public function store(Request $request): NotaTecnicaArquivoResource
    {
        $file = $request->file('file');
        EsialNotaTecnicaAnexo::create([
            'esial_nota_tecnica_id' => $request->id,
            'nome' => $file->getClientOriginalName(),
            'conteudo' => base64_encode(file_get_contents($file->getRealPath())),
            'user_id' => auth()->id(),
        ]);

        return $this->show($request->id);
    }

    public function show(int $id): NotaTecnicaArquivoResource
    {
        return new NotaTecnicaArquivoResource(
            EsialNotaTecnicaAnexo::where('esial_nota_tecnica_id', $id)
                ->select('id', 'nome', 'nota_tecnica_sei')
                ->get()
        );
    }

    public function download(EsialNotaTecnicaAnexo $anexo)
    {
        return base64_decode($anexo->conteudo);
    }

    public function destroy(int $id)
    {
        EsialNotaTecnicaAnexo::where('id', $id)->delete();
    }
}
