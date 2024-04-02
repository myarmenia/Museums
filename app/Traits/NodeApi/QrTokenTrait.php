<?php
declare(strict_types=1);

namespace App\Traits\NodeApi;

use App\Models\PurchasedItem;
use App\Models\TicketQr;
use Illuminate\Support\Facades\DB;
use Http;

trait QrTokenTrait
{
    public function getTokenQr(int $purchaseId): bool|array
    {
        $url = env('NODE_API_URL') . 'getQr';

        $allData = [];

        try {
            DB::beginTransaction();
            $allPurchases = PurchasedItem::where('purchase_id', $purchaseId)->where('type', '!=', 'product')->get();

            $purchasesKeys = [];
            foreach ($allPurchases as $key => $item) {
                $purchasesKeys[$item->type] = array_key_exists($item->type, $purchasesKeys)
                    ? $purchasesKeys[$item->type] + $item->quantity
                    : $item->quantity;
            }
    
            $data = $this->getReqQrToken($url, $purchasesKeys);
    
            foreach ($allPurchases as $key => $item) {
                $quantity = $item->quantity;
    
                for ($i = 0; $i < $quantity; $i++) {
                    $type = $item->type;
                    $token = $data[$type][0]['unique_token'];
                    $path = $data[$type][0]['qr_path'];
    
                    $newData = [
                        'museum_id' => $item->museum_id,
                        'purchased_item_id' => $item->purchase_id,
                        'item_relation_id' => $item->item_relation_id,
                        'token' => $token,
                        'path' => $path,
                        'type' => $type,
                        'price' => $item->total_price,
                    ];
    
                    $allData[] = $newData;
                    array_shift($data[$type]);
                }
            }
    
            $insert = TicketQr::insert($allData);

            if (!$insert) {
                DB::rollBack();
                return false;
            }
    
            DB::commit();

            return TicketQr::where('purchased_item_id', $purchaseId)->get()->pluck('id')->toArray();
            
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        } catch (\Error $e) {
            DB::rollBack();
            dd($e->getMessage());
            return false;
        }

    }

    public function getReqQrToken(string $url, array $data): array
    {
        $req = Http::post($url, $data);
        $response = $req->getBody()->getContents();
        $response = json_decode($response, true);

        return $response;
    }
}
