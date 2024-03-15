<?php

namespace App\Http\Resources\API\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'chat_id' => $this->id,
            'title' => $this->title,
            'education_program_type' => $this->education_program_type,
            'messages' => MessageResource::collection($this->messages),
        ];
    }
}
