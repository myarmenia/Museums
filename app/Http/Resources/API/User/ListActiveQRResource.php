<?php

namespace App\Http\Resources\API\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ListActiveQRResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $base64String = null;
        if (Storage::exists($this->path)) {
            $qr_image = Storage::get($this->path);
            $mimeType = Storage::mimeType($this->path);
            $base64 = base64_encode($qr_image);
            $base64String = 'data:' . $mimeType . ';base64,' . $base64;
        }

        $color = $this->type == 'event' || $this->type == 'event-config' ? 'event-' . $this->sub_type : $this->type;

        $data = [
          'id' => $this->id,
          'museum_id' => $this->museum_id ?? null,
          'path' => route('get-file', ['path' => $this->path]),
          'path_base64' => $base64String,
          'type' => $this->type,
          'price' => $this->price,
          'color' => ticketColors()[$color]
        ];



        if ($this->type == 'united') {
            $translations = $this->purchased_item->purchase_united_tickets->pluck('museum')->pluck('translations')->flatten();
            $names = $translations->where('lang', session("languages"))->pluck('name');
        }
        else{
            if ($this->type == 'subscription') {
                $data['date'] = date('d.m.Y', strtotime($this->created_at));
            }
            if ($this->type == 'event-config') {
                $data['date'] = date('d.m', strtotime($this->event_config->day)) . '|' . date('H:i', strtotime($this->event_config->start_time)) . '-' . date('H:i', strtotime($this->event_config->end_time));
            }
            if ($this->type == 'event') {
              $data['date'] = date('d.m', strtotime($this->event->start_date)) . '-' . date('d.m', strtotime($this->event->end_date));
            }

            $data['museum_address'] = $this->museum->getCurrentTranslation[0]->address;
            $names = [$this->museum->translation(session("languages"))->name];

        }

        $data['museum_name'] = $names;

        return $data;
    }
}
