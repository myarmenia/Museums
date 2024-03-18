<?php
namespace App\Traits\Museum\Tickets;

use App\Models\Ticket;
use App\Models\GuideService;
use App\Models\TicketSubscriptionSetting;
use App\Models\TicketUnitedSetting;


trait TicketTypeTrait
{
  public function getType($type)
  {
    $data = [];
    switch ($type) {
        case 'standart':
            $data = [
                [
                  'id' => $this->id,
                  'price' => ticketType('standart')->coefficient * $this->price,
                  'min' => ticketType('standart')->min_quantity,
                  'max' => ticketType('standart')->max_quantity,
                  'type' => 'standart'
                ],
                [
                  'id' => $this->id,
                  'price' => ticketType('discount')->coefficient * $this->price,
                  'min' => ticketType('discount')->min_quantity,
                  'max' => ticketType('discount')->max_quantity,
                  'type' => 'discount'

                ],
                [
                  'id' => $this->id,
                  'price' => ticketType('free')->coefficient * $this->price,
                  'min' => ticketType('free')->min_quantity,
                  'max' => ticketType('free')->max_quantity,
                  'type' => 'free'

                ]

            ];
            break;
        case 'subscription':
            $data = [
                  'id' => $this->id,
                  'price' => $this->price,
                  'min' => ticketType('subscription')->min_quantity,
                  'max' => ticketType('subscription')->max_quantity,
                  'type' => 'subscription'

                ];
            break;
        case 'united':
          $data = [
            'id' => $this->id,
            'price' => $this->united_ticket_price(),
            'min' => ticketType('united')->min_quantity,
            'max' => ticketType('united')->max_quantity,
            'type' => 'united'

          ];
          break;

    }


    return $data;
  }


}
