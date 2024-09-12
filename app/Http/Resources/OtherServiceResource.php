<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OtherServiceResource extends JsonResource
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
          "museum_id" => $this->museum_id,
          "name" => $this->translation(app()->getLocale())->name,
          "price" => $this->price

        ];
    }
}
