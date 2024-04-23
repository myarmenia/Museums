<?php

namespace App\Http\Resources\Notification;

use App\Http\Resources\EventImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
          "id"=>$this->id,
          "notifiable_id"=>$this->notifiable_id,
          "event_id"=>$this->data['event']['id'],
          "event_name"=>$this->data['event']['lang'][session("languages")]['name'],
          "start_date"=>$this->data['event']['start_date'],
          "end_date" =>$this->data['event']['end_date'],
          "link" =>$this->data['event']['link'],
          'image' =>self::event_img($this->data['event']['id']),

        ];
    }
    public function event_img($eventId){

      $image=Image::where(['imageable_id'=>$eventId,'imageable_type'=>'App\Models\Event'])->first();

      return  new EventImageResource($image);

    }
}
