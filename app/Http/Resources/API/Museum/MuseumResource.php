<?php

namespace App\Http\Resources\API\Museum;

use App\Http\Resources\API\Tickets\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuseumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $translations = $this->translations[0];
        $mainPhotoPath = $this->images->where('main', 1)->first()->path;
        $request['type'] = 'standart';

        return [
            'id' => $this->id,
            'region' => $this->region->name,
            'address' => $translations->address,
            'description' => $translations->description,
            'tickets' => new TicketResource($this->standart_tickets),
            'name' => $translations->name,
            'photo'=> $mainPhotoPath ? route('get-file', ['path' => $mainPhotoPath]) : '',
            'working_days'=>$translations->working_days
        ];
    }
}
