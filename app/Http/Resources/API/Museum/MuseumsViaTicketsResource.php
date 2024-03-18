<?php

namespace App\Http\Resources\API\Museum;


use App\Http\Resources\API\Tickets\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuseumsViaTicketsResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, mixed>
   */
  public function toArray(Request $request): array
  {

    $method = $request->type . '_tickets';

    // $tickets = class_basename($this->{$method}()) == 'HasOne' ? new TicketResource($this->{$method}) : TicketResource::collection($this->{$method});

    return [
      'id' => $this->id,
      'region_name' => $this->region->name,
      'name' => $this->translation(session("languages"))->name,
      'tickets' => new TicketResource($this->{$method})
     
    ];
  }
}
