<?php
namespace App\Traits\Museum\Tickets;

use App\Models\Ticket;
use App\Models\GuideService;
use App\Models\TicketSubscriptionSetting;
use App\Models\TicketUnitedSetting;


trait TicketsTrait
{
  public function getTicketsViaType($type)
  {
      return TicketUnitedSetting::first();
  }


}
