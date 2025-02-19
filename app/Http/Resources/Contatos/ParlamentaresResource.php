<?php

namespace App\Http\Resources\Contatos;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParlamentaresResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
