<?php

namespace App\Http\Resources\API\Tickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventsTicketsResource extends JsonResource
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
                  'price' => $this->price,
                  'min' => ticketType('event')->min_quantity,
                  'max' => $this->visitors_quantity_limitation - $this->visitors_quantity,
                  'type' => 'event'
              ];
    }
}
