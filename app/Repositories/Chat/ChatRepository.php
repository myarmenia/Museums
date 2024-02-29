<?php
namespace App\Repositories\Chat;

use App\Interfaces\Chat\ChatInterface;
use App\Models\Chat;
use App\Models\Message;



class ChatRepository implements ChatInterface
{
   public function getSuperAdminRooms()
   {
        return Chat::with(['visitor', 'messages' => function ($query) {
            $query->orderBy('id', 'DESC')->first(); 
        }])->whereNull('museum_id')->orderBy('id', 'DESC')->paginate(5);
   }

   public function getMuseumRooms($museumId)
   {
        return Chat::where('museum_id', $museumId)->orderBy('id', 'DESC')->paginate(5);
   }

   public function getRoomMessage($id)
   {
        return Chat::with(['visitor', 'messages' => function ($query) {
            $query->orderBy('id', 'ASC')->get(); 
        }])->find($id);
   }

   public function addAdminMessage($data)
   {
        $message =  [
            'chat_id' => $data['chat_id'],
            'text' => $data['text'],
            'type' => 'museum',
        ];

        return Message::create($message);

   }

}