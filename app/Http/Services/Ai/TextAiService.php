<?php

namespace App\Http\Services\Ai;

class TextAiService
{
    public static function summarize(string $text): string
    {
        return Chat::engine("Resuma a lei a seguir, retornando no máximo 15 linhas. Faça a tarefa como se fosse um experiente analista legislativo: {$text}");
    }

    public static function format(string $text): string
    {
        return Chat::engine("Formate o texto que segue, para ser inserido entre divs de uma página da internet, use TailwindCss para formatação mas não importe a biblioteca e a cor do background ser sky-700. Atente-se para o fato de ser uma lei, não omita ou altere o texto, não adicione comentário e retorne apenas o texto configurado: {$text}");
    }

    public static function formatQuill(string $text): string
    {
        return Chat::engine("Formate o texto que segue para ser usando com Quill.Js. Não coloque entre div ou adicione caracteres especiais. Não inclua comentarios: {$text}");
    }

    public static function generate(string $text): string
    {
        return Chat::engine($text);
    }
}
