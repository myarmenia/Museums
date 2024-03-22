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

        $data = [
              'id' => $this->id,
              'type' => $this->type,
              'museum_name' => $this->type == 'united' ? '' : $this->museum->translation(session("languages"))->name,
              'quantity' => $this->quantity,
              'total_price' => $this->total_price

            ];

        if($this->type == 'event'){
            $data['date'] = date('m-d-Y', strtotime($this->event_config->day)) . ' ' . date('H:i', strtotime($this->event_config->start_time)) . '-' . date('H:i', strtotime($this->event_config->end_time));
        }

        return $data;
    }
}
