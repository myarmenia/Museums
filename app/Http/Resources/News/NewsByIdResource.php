<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsByIdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'image' => isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
            'description' => $this->news_translations->first()->description,
            'title' => $this->news_translations->first()->title,


        ];
    }
}
