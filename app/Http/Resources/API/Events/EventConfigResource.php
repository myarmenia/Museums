<?php

namespace App\Http\Resources\API\Events;

use App\Http\Resources\API\Tickets\EventsTicketsResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventConfigResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id'=>$this->id,
          'event_id'=>$this->event_id,
          'day'=> date('d-m-Y', strtotime($this->day)),
          'start_time'=>$this->start_time,
          'end_time' =>$this->end_time,
          'visitors_quantity_limitation'=>$this->visitors_quantity_limitation,
          'price'=>$this->price,
          'tickets'=>new EventsTicketsResource($this),

        ];
    }
}
