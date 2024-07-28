<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
        'created_at' => $this->created_at->format("d.m.Y"),
        'image' => isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
        'name' => $this-> translation(session("languages"))->name,
        'price'=> $this->price,

        ];
    }
}
