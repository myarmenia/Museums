<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    
    public function index(Request $request)
    {
        $data = Chat::orderBy('id', 'DESC')->paginate(5);

        return view('content.chat.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }
}
