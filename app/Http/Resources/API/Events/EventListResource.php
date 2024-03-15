<?php

namespace App\Http\Resources\API\Events;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'museum_id'=> $this->museum_id,
          'price' => $this->price,
          'name' => $this->translation(session('languages'))->name,
          'description' =>$this->translation(session('languages'))->description,
          'start_date'=> $this->start_date,
          'end_date' => $this->end_date,
          'region'=> $this->museum->region->name,
          'event_configs'=>EventConfigResource::collection($this->event_configs),
         
        ];
    }
}
