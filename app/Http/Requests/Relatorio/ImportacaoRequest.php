<?php

namespace App\Http\Requests\Relatorio;

use Illuminate\Foundation\Http\FormRequest;

class ImportacaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required',
            'csv' => 'required|file|mimes:csv,txt',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'O campo tipo é obrigatório!',
            'csv.required' => 'Um arquivo deve ser escolhido é obrigatório!',
            'csv.mimes' => 'O arquivo deve ser do tipo: csv ou txt!',
        ];
    }
}
