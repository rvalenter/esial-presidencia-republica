<?php

namespace App\Enums\Legislativo;

enum ColegiadoReuniaoEnum: int
{
    case DELIBERATIVA = 1;
    case ORDINARIA = 2;
    case AUDIENCIA_PUBLICA = 3;
    case SESSAO_EXTRAORDINARIA = 4;
    case SESSAO_SOLENE = 5;
    case COMISSAO_PERMANENTE = 6;
    case COMISSAO_TEMPORARIA = 7;
    case COMISSAO_ESPECIAL = 8;
    case REUNIAO_CONJUNTA_DE_COMISSOES = 9;
    case LIDERANCAS = 10;
    case SESSAO_PREPARATORIA = 11;
    case SESSAO_ITINERANTE = 12;
    case COMISSOES_MISTAS = 13;
    case COMISSOES_TEMATICAS = 14;
    case SESSAO_DE_PERGUNTAS_E_RESPOSTAS = 15;
    case REUNIAO_ADMINISTRATIVA = 16;
    case COMISSAO_DE_REPRESENTACAO = 17;
    case COMISSAO_DE_INQUERITO = 18;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::DELIBERATIVA => 'Deliberativa',
            self::ORDINARIA => 'Ordinária',
            self::AUDIENCIA_PUBLICA => 'Audiência Pública',
            self::SESSAO_EXTRAORDINARIA => 'Sessão Extraordinária',
            self::SESSAO_SOLENE => 'Sessão Solene',
            self::COMISSAO_PERMANENTE => 'Comissão Permanente',
            self::COMISSAO_TEMPORARIA => 'Comissão Temporária',
            self::COMISSAO_ESPECIAL => 'Comissão Especial',
            self::REUNIAO_CONJUNTA_DE_COMISSOES => 'Reunião Conjunta de Comissões',
            self::LIDERANCAS => 'Lideranças',
            self::SESSAO_PREPARATORIA => 'Sessão Preparatória',
            self::SESSAO_ITINERANTE => 'Sessão Itinerante',
            self::COMISSOES_MISTAS => 'Comissões Mistas',
            self::COMISSOES_TEMATICAS => 'Comissões Temáticas',
            self::SESSAO_DE_PERGUNTAS_E_RESPOSTAS => 'Sessão de Perguntas e Respostas',
            self::REUNIAO_ADMINISTRATIVA => 'Reunião Administrativa',
            self::COMISSAO_DE_REPRESENTACAO => 'Comissão de Representação',
            self::COMISSAO_DE_INQUERITO => 'Comissão de Inquérito',
        };
    }
}
