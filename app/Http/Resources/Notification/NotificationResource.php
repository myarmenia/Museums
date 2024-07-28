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

        $data = json_decode($this->data, true);

        return [
          "id"=>$this->id,
          "notifiable_id"=>$this->notifiable_id,
          "event_id"=> $this->notifiable_id,
          "event_name"=>$data['event']['lang'][session("languages")]['name'],
          "start_date"=>$data['event']['start_date'],
          "end_date" =>$data['event']['end_date'],
          "link" =>$data['event']['link'],
          'image' =>self::event_img($data['event']['id']),

        ];
    }
    public function event_img($eventId){

      $image=Image::where(['imageable_id'=>$eventId,'imageable_type'=>'App\Models\Event'])->first();

      return  new EventImageResource($image);

    }
}
