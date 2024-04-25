<?php

namespace App\Http\Controllers\API\Tickets;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Traits\Museum\Tickets\TicketsTrait;
use Illuminate\Http\Request;

class UnitedTicketSettingsController extends BaseController
{
    use TicketsTrait;
    public function __invoke(Request $request)
    {

      $data = $this->getUnitedSettings();

      return $this->sendResponse($data, 'success');

    }
}
