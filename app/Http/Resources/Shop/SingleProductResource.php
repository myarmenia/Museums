<?php

namespace App\Http\Resources\Shop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingleProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
  // dd($this->id);
        return [
          'id' => $this->id,
          'museum_id'=>$this->museum_id,
          'museum_name'=>$this->museum->translation(session("languages"))->name,
          'product_category_id'=>$this->category->translation(session("languages"))->name,
          'image' => isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
          'name' => $this-> translation(session("languages"))->name,
          'price'=> $this->price,
          'similar_products'=>SimilarProductResource::collection($this->similar_products($this->museum_id, $this->id))

        ];
    }
}
