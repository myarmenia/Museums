<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            "name" => $this->name,
            "process_time" => $this->process_time,
            "creation_date_at" => $this->creation_date_at,
            "link_project" => $this->link_project,
            "link_app_store" => $this->link_app_store,
            "link_play_market" => $this->link_play_market,
            "project_language" => $this->project_language,
            "type" => $this->type,
            "images" =>  CreateImagePathResource::collection($this->images),
            "translation" => getProjectDescription($this->translation),
        ];
    }

}
