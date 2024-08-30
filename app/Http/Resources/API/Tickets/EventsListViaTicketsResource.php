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
          $price_array[0]=[

            "price"=>$this->price,
            "sub_type"=>'standart'

        ];
        if($this->discount_price!=null){

          $price_array[1]=[
            "price"=>$this->discount_price,
            "sub_type"=>'discount'
          ];
        }


        $configs = $this->event_configs;
        $configs = isset($request->start_date) ? $configs->where('day', '>=', $request->start_date) : $configs;
        $configs = isset($request->end_date) ? $configs->where('day', '<=', $request->end_date) : $configs;

        return [
          'id' => $this->id,
          'museum_id' => $this->museum_id,
          'name' => $this->translation(session('languages'))->name,
          'event_configs' => EventConfigResource::collection($configs),
          'start_date' => date('d-m-Y', strtotime($this->start_date)),
          'end_date' => date('d-m-Y', strtotime($this->end_date)),
          'price' => $this->price,
          'all_prices'=>$price_array,
          'style' => $this->style

        ];
    }
}
