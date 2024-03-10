<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index(Request $request)
    {
        $data = $this->chatService->getRooms();

        return view('content.chat.index', compact('data'))
               ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function getRoomMessage($id)
    {
        $data = $this->chatService->getRoomMessage($id);

        if(!$data) {
            return redirect()->back();
        }

        return view('content.chat.messages', compact('data'));
    }

    public function addMessage(Request $request)
    {
        $addMessage = $this->chatService->addAdminMessage($request->all());

        return response()->json(['status' => 'success', 'message' => $addMessage]);
    }
}
