<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsByCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'category' => $this->resource['categoryName'],
            'items' => NewsResource::collection($this->resource['news']),
        ];
    }

    public function with($request)
    {
        return [
            'pagination' => [
                'total' => $this->resource['news']->total(),
                'per_page' => $this->resource['news']->perPage(),
                'current_page' => $this->resource['news']->currentPage(),
                'last_page' => $this->resource['news']->lastPage(),
                'from' => $this->resource['news']->firstItem(),
                'to' => $this->resource['news']->lastItem(),
            ],
        ];
    }
}
