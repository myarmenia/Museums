<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Partners\PartnerRequest;
use App\Models\Partner;
use App\Traits\Museum\Partners;
use App\Traits\StoreTrait;
use Illuminate\Http\Request;

class PartnerStoreController extends Controller
{
    use StoreTrait;

    public function model()
    {
      return Partner::class;
    }

    public function __invoke(PartnerRequest $request)
    {

      // $request['ticket'] = isset($request->ticket) ? true : 0;
      $partner = $this->itemStore($request);

      if ($partner) {

        return redirect()->route('partners_list');
      }
    }
}
