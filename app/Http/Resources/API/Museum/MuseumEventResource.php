<?php

namespace App\Http\Resources\API\Museum;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuseumEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $translations = $this->translation(session('languages'));

        return [
            'id' => $this->id,
            'name' => $translations->name,
            'photo' => route('get-file', ['path' => $this->image->path]),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
