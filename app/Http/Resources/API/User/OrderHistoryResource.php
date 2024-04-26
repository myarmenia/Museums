<?php

namespace App\Http\Resources\API\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
          'id' => $this->id,
          'order_id' => $this->purchase->payment->payment_order_id,
          'type' => $this->type,
          'quantity' => $this->quantity,
          'total_price' => $this->total_price,
          'date' => date('d-m-Y', strtotime($this->created_at)),
          'status' => $this->purchase->payment->status

        ];

        if($this->type == 'united'){
          $translations = $this->purchase_united_tickets->pluck('museum')->pluck('translations')->flatten();
          $names = $translations->where('lang', session("languages"))->pluck('name');
        }

        $data['museum_name'] = $this->type == 'united' ? $names : [$this->museum->translation(session("languages"))->name];

        return $data;
    }
}
