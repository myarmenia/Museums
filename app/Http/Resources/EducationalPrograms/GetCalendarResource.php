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
          "title" => $this->educational_program ? $this->educational_program->translation('am')->name : 'Էքսկուրսիա',
          "start" => $this->date. " " . $this->time,
        ];
    }
}
