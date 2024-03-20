<?php

namespace App\Http\Resources\API\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
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
        'name' => $this->product->translation(session("languages"))->name,
        'image' => route('get-file', ['path' => $this->product->images->path]),
        'quantity' => $this->quantity,
        'total_price' => $this->total_price

      ];
    }
}
