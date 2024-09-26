<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\Partners\PartnerRequest;
use App\Models\Partner;
use App\Traits\UpdateTrait;
use Illuminate\Http\Request;

class PartnerUpdateController extends Controller
{
    use UpdateTrait;

    public function model()
    {
      return Partner::class;
    }
    public function __invoke(PartnerRequest $request, string $id)
    {

      // $request['ticket'] = isset($request->ticket) ? true : 0;
      $partner = $this->itemUpdate($request, $id);

      if ($partner) {

        return redirect()->back();
      }

    }
}
