<?php

namespace App\Http\Resources\API\Tickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

      // if($request->type == 'standart'){

      // }
      // return $this->tickets();
      return $this->getType($request->type);
      //   return [
      //     'id' => $this->id,
      //     // 'type' => $this->region->name,
      //     'price' => $this->price,
      //     // 'min' =>
      //     // 'max' =>

      // ];
    }
}
