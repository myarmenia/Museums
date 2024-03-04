<?php

namespace App\Http\Resources\EducationalPrograms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationalProgramsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          "id" => $this->id,
          "name" => $this->translation(session("languages"))->name,
          "description" => $this->translation(session("languages"))->description,
          "price" => $this->price,
          "min_quantity" => $this->min_quantity,
          "max_quantity" => $this->max_quantity,
        ];
    }
}
