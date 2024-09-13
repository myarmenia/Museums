<?php

namespace App\Http\Controllers\api\OtherService;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\OtherServiceResource;
use App\Interfaces\OtherService\OtherServiceInterface;
use App\Models\OtherService;
use App\Repositories\OtherService\OtherServiceRepository;
use App\Services\API\OtherService\OtherServService;
use Illuminate\Http\Request;

class OtherServiceController extends BaseController
{

  public function __construct(protected OtherServService $otherServService){

  }
     public function show($id){

      $data = $this->otherServService->getAllData($id);

      return $this->sendResponse(OtherServiceResource::collection($data),'success');

     }
}
