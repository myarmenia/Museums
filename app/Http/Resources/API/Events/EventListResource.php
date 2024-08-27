<?php

namespace App\Http\Resources\API\Events;

use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
          'id' =>$this->id,
          'museum_id'=> $this->museum_id,
          'museum_name'=> $this->museum->translation(session('languages'))->name,
          'style'=>$this->style,
          'price' => $this->price,
          'discount_price'=> $this->discount_price,
          'name' => $this->translation(session('languages'))->name,
          'description' =>$this->translation(session('languages'))->description,
          'start_date'=> \Carbon\Carbon::parse($this->start_date)->format("d/m/Y"),
          'end_date' => \Carbon\Carbon::parse($this->end_date)->format("d/m/Y"),
          'full_date'=>\Carbon\Carbon::parse($this->start_date)->format("d.m").'-'.\Carbon\Carbon::parse($this->end_date)->format("d.m"),
          'region'=> $this->museum->region->name,
          'image'=>isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
          'event_configs'=>EventConfigResource::collection($this->event_configs),


        ];
    }
}
