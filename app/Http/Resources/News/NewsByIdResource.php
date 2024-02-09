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
            'description' => $this->translations->first()->description,
            'title' => $this->translations->first()->title,
            'categoryName' => $this->category->translations->first()->name,
            'categoryId' => $this->category->id
        ];
    }
}
