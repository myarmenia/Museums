<?php

namespace App\Http\Resources\API\Museum;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuseumsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'regions' => $this->resource['regions'],
            'museums' => MuseumResource::collection($this->resource['museums']),
        ];
    }
}
