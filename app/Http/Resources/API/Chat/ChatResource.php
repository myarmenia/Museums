<?php

namespace App\Http\Resources\API\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $translations = $this->museum->getCurrentTranslation[0];
        
        return [
            'chat_id' => $this->id,
            'title' => $this->title,
            'museum_id' => $this->museum->id,
            'museum_name' => $translations->name,
            'education_program_type' => $this->education_program_type,
            'messages' => MessageResource::collection($this->messages),
        ];
    }
}
