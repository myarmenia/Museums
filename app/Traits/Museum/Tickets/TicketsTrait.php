<?php
namespace App\Traits\Museum\Tickets;
use App\Models\Ticket;
use App\Models\GuideService;
use App\Models\TicketSchoolService;
use App\Models\TicketSchoolSetting;
use App\Models\TicketSubscriptionSetting;
use App\Models\TicketUnitedSetting;


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

  public function getGuideService()
  {
    return GuideService::where(["museum_id" => museumAccessId()])->first();
  }

  public function getUniteddeService()
  {
    return TicketUnitedSetting::first();
  }

  public function getSchoolService()
  {
    return TicketSchoolSetting::first();
  }

  public function getUnitedSettings()
  {
      return [
          "min_museum_quantity" => $this->getUniteddeService()->min_museum_quantity,
          "discount_percent" => $this->getUniteddeService()->percent
      ];
  }

}
