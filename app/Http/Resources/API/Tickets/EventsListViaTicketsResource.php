<?php

namespace App\Http\Resources\API\Tickets;

use App\Http\Resources\API\Events\EventConfigResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventsListViaTicketsResource extends JsonResource
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
          'museum_id' => $this->museum_id,
          'name' => $this->translation(session('languages'))->name,
          'event_configs' => EventConfigResource::collection($this->event_configs)

        ];
    }
}
