<?php

namespace App\Http\Resources\API\Tickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitedTicketsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $tickets = [
          'price' => $this->united_ticket_price(),
          'type' => 'united',
          'min' => ticketType('united')->min_quantity,
          'max' => ticketType('united')->max_quantity,
        ];

        $data = [
          'id' => $this->id,
          'region_name' => $this->region->name,
          'name' => $this->translation(session("languages"))->name,
          'tickets' => [$tickets]

        ];

        return $data;
    }
}
