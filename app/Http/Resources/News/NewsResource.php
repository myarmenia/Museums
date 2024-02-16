<?php

namespace App\Http\Resources\News;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    protected $count = 1;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
// dd($this->news_translations());
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'image' => isset($this->images[0])?route('get-file',['path'=>$this->images[0]->path]):null,
            'description' => $this->getAppropriateVal($this->news_translations, 'description'),
            'title' => $this->getAppropriateVal($this->news_translations, 'title'),
        ];
    }

    public function getAppropriateVal($data, $key)
    {
      // dd($key);
        $lang = session('languages');

        $data = $data->where('lang', $lang)->first();


        return $data?$data[$key] : null;

    }
}
