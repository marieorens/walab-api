<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'adress' => $this->address,
            'statut' => $this->statut,
            'examens' => ExamResource::collection($this->whenLoaded('examen')),
            'created_at' => $this->created_at
        ];
    }
}
