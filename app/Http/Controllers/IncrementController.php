<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IncrementController extends Controller
{
  public function increment($id,$count){
   
    return view('components.event-config',compact('id','count',));
  }
}
