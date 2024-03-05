<?php

namespace App\Http\Resources\EducationalPrograms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetCalendarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          "title" => 'Этап' . $this->stage_number,
          "start" => $this->created_at
        ];
    }
}
