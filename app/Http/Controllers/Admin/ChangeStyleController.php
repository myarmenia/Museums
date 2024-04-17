<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChangeStyleController extends Controller
{
    public function change_style(Request $request, $type)
    {
        session()->put('style', $type);
        
        return back();

    }
}
