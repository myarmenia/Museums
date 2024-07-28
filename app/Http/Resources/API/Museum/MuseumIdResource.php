<?php

namespace App\Http\Resources\API\Museum;

use App\Http\Resources\API\Tickets\TicketResource;
use App\Http\Resources\API\Tickets\WebTicketResource;
use App\Http\Resources\MuseumBranchesResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuseumIdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $translations = $this->translations[0];
        $mainPhotoPath = $this->images->where('main', 1)->first()->path;
        $phones = $this->phones->pluck('number')->toArray();
        $links = $this->links->pluck('link', 'name')->toArray();
        $photos = $this->images->where('main', 0)->pluck('path')->map(function (string $path) {
            return route('get-file', ['path' => $path]);
        });
        $guide =$this->guide ? ['am' => $this->guide->price_am, 'other' => $this->guide->price_other]: null;


        return [
            'id' => $this->id,
            'region' => $this->region->name,
            'address' => $translations->address,
            'name' => $translations->name,
            'description' => $translations->description,
            'working_days' => $translations->working_days,
            'director' => $translations->director_name,
            'phones' => $phones,
            'links' => $links,
            'email' => $this->email,
            'guide' => $guide,
            'photos' => $photos,
            'tickets' => new WebTicketResource($this),
            'branches' => MuseumBranchesResource::collection($this->museum_branches),
            'main_photo'=> $mainPhotoPath ? route('get-file', ['path' => $mainPhotoPath]) : ''
        ];
    }
}
