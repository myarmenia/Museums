<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuseumListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      // dd($this);
        return [
          "id" => $this->id,
          "name"=>$this->translation(session("languages"))->name,
          "region_id"=>$this->museum_geographical_location_id,
          "region_name" =>$this->region->name,
          "working_days"=>$this->translation(session("languages"))->working_days

        ];
    }
}
