<?php

namespace App\Http\Controllers\API\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\AddAdminChatMessageRequest;
use App\Http\Requests\Chat\AddChatMessageRequest;
use App\Http\Requests\Chat\AddProfileMessageRequest;
use App\Http\Resources\API\Chat\AllChatResource;
use App\Http\Resources\API\Chat\ChatResource;
use App\Http\Resources\API\Chat\MessageResource;
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

    public function getMuseumMessage($id)
    {
        $data = $this->chatService->getMuseumMessage((int) $id);

        if(empty($data)){
            return response()->json(['data' => $data]);
        };
        
        return new ChatResource($data);
    }

    public function deleteChat($id)
    {
        $deleted = $this->chatService->deleteChat((int) $id);

        if($deleted) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function getAdminMessage()
    {
        $data = $this->chatService->getAdminMessage();

        if(empty($data)){
            return response()->json(['data' => $data]);
        };
        
        return new ChatResource($data);
    }

    public function getAllMuseumsMessages()
    {
        $data = $this->chatService->getAllMuseumsMessages();

        if(empty($data)){
            return response()->json(['data' => $data]);
        };
        
        return AllChatResource::collection($data);
    }

    public function addProfileMessage(AddProfileMessageRequest $request)
    {
        $addMessage = $this->chatService->addProfileMessage($request->all());

        if($addMessage['success']){
            return response()->json(['status' => true, 'message' => new MessageResource($addMessage['message'])]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')]);
    }
    
}
