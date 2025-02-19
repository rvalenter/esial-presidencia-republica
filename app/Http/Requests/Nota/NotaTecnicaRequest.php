<?php

namespace App\Http\Requests\Nota;

use Illuminate\Foundation\Http\FormRequest;

class NotaTecnicaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pdfFile' => 'nullable|file|mimetypes:application/octet-stream,application/pdf,pdf,octet-stream|max:200000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pdfFile.mimes' => 'O arquivo deve ser do tipo: pdf!',
            'pdfFile.max' => 'O arquivo deve ter no máximo 200MB!',
        ];
    }
}
