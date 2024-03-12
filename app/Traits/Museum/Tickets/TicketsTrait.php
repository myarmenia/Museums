<?php
namespace App\Traits\Museum\Tickets;
use App\Models\Ticket;
use App\Models\TicketSubscriptionSetting;


trait TicketsTrait
{
  public function getStandart()
  {
    return Ticket::where("museum_id", museumAccessId())->first();
  }

  public function getSubscription()
  {
    return TicketSubscriptionSetting::where("museum_id", museumAccessId())->first();
  }

  // public function getGuideService()
  // {
  //   return EducationalProgramReservation::where(["museum_id" => museumAccessId()])->first();
  // }




}
