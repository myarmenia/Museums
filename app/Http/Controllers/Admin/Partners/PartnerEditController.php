<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use App\Traits\Museum\Partners;
use Illuminate\Http\Request;

class PartnerEditController extends Controller
{
    use Partners;
    public function __invoke($id)
    {

      $partner = $this->getPartner($id);

      if (!$partner) {
        return redirect()->back();
      }
      return view("content.partners.edit", compact('partner'));
    }
}
