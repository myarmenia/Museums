<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MuseumBranchesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
          'id'=>$this->id,
          'email'=>$this->email,
          'phone_number'=>[$this->phone_number],
          'name'=>$this->translation(session('languages'))->name,
          'web_site'=>$this->links? $this->links->first()->link : "",
          'description'=>$this->translation(session('languages'))->description,
          'working_days'=>$this->translation(session('languages'))->working_days,
          'address'=>$this->translation(session('languages'))->address,
          'photo'=> route('get-file', ['path' => $this->images[0]->path]),
        ];
    }
}
