<?php

namespace App\Http\Resources\Shop;

use App\Http\Resources\ProductResource;
use App\Models\Museum;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {


      return [
// dd($this->museum->name);
        'id' => $this->id,
        'museum_id'=>$this->museum_id,
        'museum_name'=>$this->museum->translation(session("languages"))->name,
        'product_category_id'=>$this->product_category_id,
        'created_at' => $this->created_at->format("d.m.Y"),
        'image' => isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
        'name' => $this-> translation(session("languages"))->name,
        'price'=> $this->price,
     ];
    }
}
