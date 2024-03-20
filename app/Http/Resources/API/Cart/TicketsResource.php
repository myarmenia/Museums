<?php

namespace App\Http\Resources\API\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketsResource extends JsonResource
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
          'type' => $this->type,
          'museum_name' => $this->museum->translation(session("languages"))->name,
          'quantity' => $this->quantity,
          'total_price' => $this->total_price

        ];
    }
}
