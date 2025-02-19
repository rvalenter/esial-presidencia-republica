<?php

namespace App\Http\Services\NotaSei;

use App\Http\Requests\Nota\NotaTecnicaRequest;
use App\Http\Services\Parses\TextService;
use App\Http\Services\PDF\PdfService;
use App\Models\Nota\EsialNotaTecnica;
use App\Models\Nota\EsialNotaTecnicaAnexo;
use App\Models\Nota\EsialNotaTecnicaConclusao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotaTecnicaSeiService
{
    public static function store(EsialNotaTecnica $notaTecnica, $data): void
    {
        if ($data['type'] == 1) {
            $user = Auth::user()->id;
            $file = $data->file('pdfFile');
            $filePath = $file->getPathname();
            $fileContent = $file->getContent();
            $fileName = $file->getClientOriginalName();

            EsialNotaTecnicaConclusao::create([
                'esial_nota_tecnica_id' => $notaTecnica->id,
                'user_id' => $user,
                'conteudo' => self::storeText($filePath),
            ]);

            EsialNotaTecnicaAnexo::create([
                'esial_nota_tecnica_id' => $notaTecnica->id,
                'user_id' => $user,
                'conteudo' => base64_encode($fileContent),
                'nome' => $fileName,
                'nota_tecnica_sei' => true,
            ]);
        }
    }

    public static function storeText(string $path): string
    {
        $pdfText = PdfService::readPdfFile($path);
        $leanText = TextService::parse($pdfText);

        return self::parseText($leanText);
    }

    public static function parseText(string $text): string
    {
        $text = preg_replace('/[^a-zA-Z0-9\s.,;\/\\\\]/', '', $text);
        $text = str_replace("\n", '<br>', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return Str::trim($text);
    }
}
