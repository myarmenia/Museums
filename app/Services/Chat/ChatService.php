<?php
namespace App\Services\Chat;
use App\Mail\SendMessageToUnverifiedUser;
use App\Models\Chat;
use App\Repositories\Chat\ChatRepository;
use Mail;

class ChatService 
{
    protected $chatRepository;
    public function __construct(ChatRepository $chatRepository)
    {
        $this->chatRepository = $chatRepository;
    }

    public function getRooms()
    {
        if($this->adminStaffCheck()){
            $rooms = $this->chatRepository->getSuperAdminRooms(); 
        }else {
            $museumId = getAuthMuseumId();
            if($museumId){
                $rooms = $this->chatRepository->getMuseumRooms($museumId);  
            }else{
                return false;
            }

        }
       
        return $rooms;
    }

    public function getRoomMessage($id)
    {
        if($this->adminStaffCheck()){
            if($chat = Chat::withTrashed()->find($id)){
                if($chat->museum_id){
                    return false;
                }
            };
        }else {
            $getChatIds = $this->getAuthChat();  

            if (!in_array($id, $getChatIds)) {
                return false;
            } 
        }

        $this->updateChatRead($id);

        return $this->chatRepository->getRoomMessage($id);
    }

    public function addAdminMessage($data)
    {
        $message = $this->chatRepository->addAdminMessage($data);

        if($email = $this->getChatVisitorEmail($data['chat_id'])) {
            $this->sendMessageToUnverifiedUser($data['text'], $email);
        }

        return $message;
    }

    public function updateChatRead($id)
    {
        return Chat::withTrashed()->where('id', $id)->update(['read' => 1]);
    }

    public function getChatVisitorEmail($id)
    {
        if($email = Chat::find($id)->email){
            return $email;
        };

        return false;
    }

    public function isChatVisitor($id)
    {
        if(Chat::find($id)->visitor_id){
            return true;
        };
 
        return false;
    }

    public function sendMessageToUnverifiedUser($text, $email)
    {
       return Mail::send(new SendMessageToUnverifiedUser($email, $text));
    }

    public function getAuthChat()
    {
        $museumId = getAuthMuseumId();
        if($museumId){
            $chat = Chat::withTrashed()->where('museum_id', $museumId)->pluck('id');
            return  $chat->all();
        };

        return [];
    }

    public function adminStaffCheck()
    {
       return  auth()->user()->hasRole(['super_admin', 'general_manager']);
    }

}
