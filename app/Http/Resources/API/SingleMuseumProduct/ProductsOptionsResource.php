<?php

namespace App\Http\Resources\API\SingleMuseumProduct;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductsOptionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      // dd($this['museum_branches']);
        return [
          'products'=>$this['products'],
          'products_category'=>$this['products_category'],
          'museum_branches'=>$this['museum_branches'],

        ];
    }
}
