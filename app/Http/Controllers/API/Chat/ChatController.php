<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\AddAdminChatMessageRequest;
use App\Http\Requests\Chat\AddChatMessageRequest;
use App\Services\API\Chat\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }
    public function addMessage(AddChatMessageRequest $request)
    {
        $addMessage = $this->chatService->addMessage($request->all());

        if($addMessage['success']){
            return response()->json(['status' => true, 'message' => $addMessage['message']]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')]);
    }

    public function addAdminMessage(AddAdminChatMessageRequest $request)
    {
        $addMessage = $this->chatService->addAdminMessage($request->all());

        if($addMessage['success']){
            return response()->json(['status' => true, 'message' => $addMessage['message']]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')]);
    }
}
