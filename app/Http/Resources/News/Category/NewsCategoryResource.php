<?php

namespace App\Http\Resources\News\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            'type' => $this->type,
            "translations" => new NewsCategoryTranslationsResource($this->translations),
        ];
    }
}
