<?php
namespace App\Repositories\OtherService;

use App\Interfaces\OtherService\OtherServiceInterface;
use App\Models\OtherService;

class OtherServiceRepository implements OtherServiceInterface
{

  public function getAllData($museumId){
    return OtherService::where(["museum_id"=>$museumId,'status'=>1])->get();
  }
}
