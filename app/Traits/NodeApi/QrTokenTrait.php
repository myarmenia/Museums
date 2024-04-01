<?php
declare(strict_types=1);
namespace App\Traits\NodeApi;
use App\Models\TicketQr;
use Http;

trait QrTokenTrait
{
    public function getTokenQr(array $purchases): bool
    {
        $url = env('NODE_API_URL').'getQr';

        foreach ($purchases as $key => $item) {
            $quantity = $item->quantity;

            for ($i=0; $i < $quantity; $i++) { 
                $data = $this->getReqQrToken($url);

                $data = [
                    'museum_id' => $item[$i]->museum_id,
                    'purchased_item_id' => $item[$i]->purchased_item_id,
                    'item_relation_id' => $item[$i]->item_relation_id,
                    'token' => $data['token'],
                    'path' => $data['path'],
                    'type' => $item[$i]->type,
                    'price' => $item[$i]->total_price,
                ];
                
                TicketQr::create($data);

            }
        }   
        
        return true;

    }

    public function getReqQrToken(string $url): array
    {
        $req = Http::get($url);
        $response = $req->getBody()->getContents();
        $response = json_decode($response, true);

        return $response;
    }
}
