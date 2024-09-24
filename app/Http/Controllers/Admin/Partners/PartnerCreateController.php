<?php

namespace App\Http\Controllers\Admin\Partners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerCreateController extends Controller
{
    public function __invoke()
    {

      return view("content.partners.create");
    }
}
