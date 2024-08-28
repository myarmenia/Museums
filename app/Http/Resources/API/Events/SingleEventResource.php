<?php

namespace App\Http\Resources\API\Events;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleEventResource extends JsonResource
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

      return [
        'id' => $this->id,
        'museum_id'=> $this->museum_id,
        'museum_name'=> $this->museum->translation(session('languages'))->name,
        'museum_phones'=>$this->museum->phones->pluck('number'),
        'style' =>$this->style,
        'all_prices'=>$price_array,
        'price' => $this->price,
        'name' => $this->translation(session('languages'))->name,
        'description' =>$this->translation(session('languages'))->description,
        'start_date'=> $this->start_date,
        'end_date' => $this->end_date,
        'region'=> $this->museum->region->name,
        'image' => isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
        'event_configs'=>EventConfigResource::collection($this->event_configs),
        'same_museum_event'=>EventListResource::collection($this->similar_event($this->museum_id, $this->id)),

      ];
    }
}
