<?php

namespace App\Http\Services\PDF;

use GuzzleHttp\Client;
use Smalot\PdfParser\Parser;

class PdfService
{
    public static function getContent(string $url): string
    {
        $client = new Client();
        $response = $client->get($url);
        $pdfContent = $response->getBody()->getContents();

        return self::readPdf($pdfContent);
    }

    public static function readPdf($pdfContent): string
    {
        $parser = new Parser();
        $pdf = $parser->parseContent($pdfContent);

        return $pdf->getText();
    }

    public static function readPdfFile($pdfContent): string
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($pdfContent);

        return $pdf->getText();
    }
}
