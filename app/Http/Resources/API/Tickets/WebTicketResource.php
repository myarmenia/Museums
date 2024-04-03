<?php

namespace App\Http\Resources\API\Tickets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebTicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tickets = $this->standart_tickets ? $this->standart_tickets->getType('standart') : [];
        if($this->aboniment && $this->aboniment->status){
            $aboniment = $this->aboniment->getType('subscription');
            array_push($tickets, $aboniment[0]);
        }

        return $tickets;

    }
}
