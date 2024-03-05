<?php
namespace App\Traits\Event;

use App\Models\Event;

trait EventTrait{
  public function getEvent($id) {
    return Event::where(["id" => $id, "museum_id" => museumAccessId()])->first();
  }


}
