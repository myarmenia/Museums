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

        if ($authId = auth('api')->id()) {
            $chatId = $this->haveChat($authId, $museumId);
            if (!$chatId) {
                $chatData = [
                    'visitor_id' => $authId,
                    'museum_id' => $museumId,
                    'title' => $data['title'],
                ];

                if (array_key_exists('education_program_type', $chatData) && $chatData['education_program_type']) {
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

            if ($message) {
                return ['success' => true, 'message' => $message];
            }
        } else {
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

            if ($message) {
                return ['success' => true, 'message' => $message];
            }
        }

        return ['success' => false, 'error' => 500];

    }

    public function haveChat($authId, $museumId)
    {
        if ($chat = Chat::where('visitor_id', $authId)->where('museum_id', $museumId)->first()) {
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

    public function updateChat($data, $id)
    {
        $this->chatRepository->updateChat($data, $id);
        return $id;
    }

    public function addAdminMessage($data)
    {
        if ($authId = auth('api')->id()) {
            $chatId = $this->haveAdminChat($authId);
            if(!$chatId){
                $chatData = [
                    "visitor_id" => $authId,
                    "visitor_name" => $data['name'],
                    "visitor_phone" => $data['phone'],
                ];
                $chatId = $this->createChat($chatData);
            }else{
                $chatData = [
                    "visitor_name" => $data['name'],
                    "visitor_phone" => $data['phone'],
                ];
                $this->updateChat($chatData, $chatId);
            }
        } else {
            $chatData = [
                "email" => $data['email'],
                "visitor_name" => $data['name'],
                "visitor_phone" => $data['phone'],
            ];

            $chatId = $this->createChat($chatData);
        }

        $messageData = [
            'chat_id' => $chatId,
            'text' => $data['text'],
            'type' => Message::TYPE_VISITOR,
        ];

        $message = $this->chatRepository->addMessage($messageData);

        if ($message) {
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

    public function haveAdminChat($authId)
    {
        if ($chat = Chat::where('visitor_id', $authId)->whereNull('museum_id')->first()) {
            return $chat->id;
        }

        return false;
    }

    public function addProfileMessage($data)
    {
        $authUserId = auth('api')->id();

        $museumId = $data['museum_id'] ? $data['museum_id'] : null;

        $chat = Chat::where(['visitor_id' => $authUserId, 'museum_id' => $museumId, 'id' => $data['chat_id']])->first();

        if($chat){
            $chat->update(['read' => 0]);
            $messageData = [
                'type' => Message::TYPE_VISITOR,
                'text' => $data['text'],
                'chat_id' => $data['chat_id'],
            ];

            $message = $this->chatRepository->addMessage($messageData);

            if ($message) {
                return ['success' => true, 'message' => $message];
            }
        }

        return ['success' => false, 'error' => 500];
    }

}
