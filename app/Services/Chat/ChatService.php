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
        if(isSuperAdmin()){
            $rooms = $this->chatRepository->getSuperAdminRooms(); 
        }else {
            $museumId = haveMuseum();
            $rooms = $this->chatRepository->getMuseumRooms($museumId);  
        }
       
        return $rooms;
    }

    public function getRoomMessage($id)
    {
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
        return Chat::where('id', $id)->update(['read' => 0]);
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
}
