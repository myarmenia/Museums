<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use App\Traits\Museum\Partners;
use Illuminate\Http\Request;

class PartnerListController extends Controller
{
    use Partners;
    public function __invoke()
    {

      $data = $this->getAllPartners();

      return view("content.partners.index", compact('data'));
    }
}
