<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimilarProductResource extends JsonResource
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
          'museum_id'=>$this->museum_id,
          // 'product_category_id'=>__('product-categories.'.$this->category->key),
          'created_at' => $this->created_at->format("d.m.Y"),
          'image' => isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
          'name' => $this-> translation(session("languages"))->name,
          'price'=> $this->price,
        ];
    }
}
