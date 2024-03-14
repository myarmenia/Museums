<?php
namespace App\Services\API\Chat;
use App\Mail\SendMessageToUnverifiedUser;
use App\Models\Chat;
use App\Models\Message;
use App\Repositories\Chat\ChatRepository;
use Mail;

class ChatService 
{
    protected $chatRepository;
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function addMessage($data)
    {
        $museumId = (int) $data['museum_id'];

        if($authId = auth('api')->id()){
            $chatId = $this->haveChat($authId,  $museumId);
            if(!$chatId){
                $chatData = [
                    'visitor_id' => $authId,
                    'museum_id' => $museumId,
                    'title' => $data['title'],
                ];

                if($chatData['education_program_type']){
                    $chatData['education_program_type'] = $data['education_program_type'];
                }

                $chatId = $this->createChat($chatData);
            }
            
            $updateData = [
                'chat_id' => $chatId,
                'text' => $data['text'],
                'type' => Message::TYPE_VISITOR,
            ];

            $message = $this->chatRepository->addMessage($updateData);

            if($message){
                return ['success' => true, 'message' => $message];
            }
        }else {
            $chatData = [
                'email' => $data['email'],
                'museum_id' => $museumId,
                'title' => $data['title'],
            ];

            $chatId = $this->createChat($chatData);

            $updateData = [
                'chat_id' => $chatId,
                'text' => $data['text'],
                'type' => Message::TYPE_VISITOR,
            ];

            $message = $this->chatRepository->addMessage($updateData);

            if($message){
                return ['success' => true, 'message' => $message];
            }
        }

        return ['success' => false, 'error' => 500];

    }

    public function haveChat($authId, $museumId)
    {
        if($chat = Chat::where('visitor_id', $authId)->where('museum_id', $museumId)->first()){
            $chat->update(['read' => 0]);
            return $chat->id;
        }

        return false;
    }

    public function createChat($data)
    {
        $chat = $this->chatRepository->createChat($data);
        return $chat->id;
    }

    public function addAdminMessage($data)
    {
        $chatData = [];

        if($authId = auth('api')->id()){
            $chatData = [
                'visitor_id' => $authId,
            ];
        }else {
            $chatData = [
                'email' => $data['email'],
            ];
        }

        $chatId = $this->createChat($chatData);

        $messageData = [
            'chat_id' => $chatId,
            'text' => $data['text'],
            'type' => Message::TYPE_VISITOR,
        ];

        $message = $this->chatRepository->addMessage($messageData);

        if($message){
            return ['success' => true, 'message' => $message];
        }

        return ['success' => false, 'error' => 500];
    }

    public function getMuseumMessage($museumId)
    {
        $userId = auth('api')->id();

        $data = $this->chatRepository->getMuseumMessage($museumId, $userId);
        
        return $data ? $data : [];
    }

    public function deleteChat($id)
    {
        $authId = auth('api')->id();

        return Chat::where('visitor_id', $authId)->where('id', $id)->delete();
    }

    public function getAdminMessage()
    {
        $userId = auth('api')->id();

        $data = $this->chatRepository->getAdminMessage($userId);
        
        return $data ? $data : [];
    }

    public function getAllMuseumsMessages()
    {
        $userId = auth('api')->id();
        
        $data = $this->chatRepository->getAllMuseumsMessages($userId);
        
        return $data ? $data : [];
    }

    
}
