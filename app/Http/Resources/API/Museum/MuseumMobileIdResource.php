<?php

namespace App\Http\Resources\API\Museum;

use App\Http\Resources\API\Tickets\TicketResource;
use App\Http\Resources\EducationalPrograms\EducationalProgramsResource;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\MuseumBranchesResource;

class MuseumMobileIdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      
        $translations = $this->translation(session("languages"));
        $mainPhotoPath = $this->images->where('main', 1)->first()->path;
        $phones = $this->phones->pluck('number')->toArray();
        $links = $this->links->pluck('link', 'name')->toArray();
        $photos = $this->images->where('main', 0)->pluck('path')->map(function (string $path) {
            return route('get-file', ['path' => $path]);
        });
        $request['type'] = 'standart';
        $guide =$this->guide ? ['price_am' => $this->guide->price_am, 'price_other' => $this->guide->price_other]: null;

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
            'guide' => $guide,
            'photos' => $photos,
            'tickets' => new TicketResource($this->standart_tickets),
            'events' => MuseumEventResource::collection($this->events),
            'branches' => MuseumBranchesResource::collection($this->museum_branches),
            'educational_programs' => EducationalProgramsResource::collection($this->educational_programs),
            'products' => ProductResource::collection($this->products),
            'main_photo'=> $mainPhotoPath ? route('get-file', ['path' => $mainPhotoPath]) : ''
        ];
    }
}
