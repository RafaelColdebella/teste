<?php

namespace App\Http\Resources\Species;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->public_id,
            "name" => $this->name,
            "tax" => $this->tax,
            "created_at" => $this->created_at
        ];
    }
}
