<?php
namespace App\Services\API\OtherService;

use App\Repositories\OtherService;
use App\Repositories\OtherService\OtherServiceRepository;

class OtherServService
{
  public function __construct(protected OtherServiceRepository $otherServiceRepository) {

          $this->otherServiceRepository=$otherServiceRepository;
  }
  public function getAllData($museumId)
  {
      return $this->otherServiceRepository->getAllData($museumId);
  }

}

