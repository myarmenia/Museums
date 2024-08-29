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
              'ticket_id' => $this->item_relation_id,
              'type' => $this->type,
              'sub_type' => $this->sub_type,
              'museum_name' => $this->type == 'united' ? '' : $this->museum->translation(session("languages"))->name,
              'quantity' => $this->quantity,
              'total_price' => $this->total_price

            ];

        $data['museum_ids'] = $this->type == 'united' ? $this->cart_united_tickets->pluck('museum_id')->toArray() : '';

        if($this->type == 'event-config'){
            $data['date'] = date('m.d.Y', strtotime($this->event_config->day)) . ' ' . date('H:i', strtotime($this->event_config->start_time)) . '-' . date('H:i', strtotime($this->event_config->end_time));
        }

        if ($this->type == 'event') {
          $data['date'] = date('m.d.Y', strtotime($this->event->start_date)) . '   ' . date('m.d.Y', strtotime($this->event->end_date));
        }

        return $data;
    }
}
