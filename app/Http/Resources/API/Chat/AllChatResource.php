<?php

namespace App\Http\Resources\API\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $translations = $this->museum->getCurrentTranslation[0];
        $mainPhotoPath = $this->museum->images->where('main', 1)->first()->path;

        return [
            'chat_id' => $this->id,
            'title' => $this->title,
            'museum_id' => $this->museum->id,
            'museum_name' => $translations->name,
            'museum_photo'=> $mainPhotoPath ? route('get-file', ['path' => $mainPhotoPath]) : '',
            'education_program_type' => $this->education_program_type,
            'messages' => MessageResource::collection($this->messages),
        ];
    }
}
