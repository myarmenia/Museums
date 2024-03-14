<?php

namespace App\Http\Resources\API\Events;

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
          'event_id'=>$this->event_id,
          'day'=>$this->day,
          'start_time'=>$this->start_time,
          'end_time' =>$this->end_time,
          'visitors_quantity_limitation'=>$this->visitors_quantity_limitation,
          'price'=>$this->price

        ];
    }
}
