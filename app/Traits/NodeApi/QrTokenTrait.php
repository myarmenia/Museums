<?php
declare(strict_types=1);

namespace App\Traits\NodeApi;

use App\Models\PurchasedItem;
use App\Models\TicketQr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Http;

trait QrTokenTrait
{
    public function getTokenQr(int $purchaseId): bool|object
    {
        $url = env('NODE_API_URL') . 'getQr';

        $allData = [];

        $unusedTypes = [
            'product',
            'guide',
        ];

        try {
            DB::beginTransaction();
            $allPurchases = PurchasedItem::where('purchase_id', $purchaseId)->whereNotIn('type', $unusedTypes)->get();
            $purchasesKeys = [];
            foreach ($allPurchases as $key => $item) {
                $purchasesKeys[$item->type] = array_key_exists($item->type, $purchasesKeys)
                    ? $purchasesKeys[$item->type] + $item->quantity
                    : $item->quantity;
            }
            $data = $this->getReqQrToken($url, $purchasesKeys);
            $addedItemsToken = [];
            foreach ($allPurchases as $key => $item) {
                $quantity = $item->quantity;
                $priceOneTicket = (int) $item->total_price / (int) $item->quantity;

                for ($i = 0; $i < $quantity; $i++) {
                    $type = $item->type;
                    $token = $data[$type][0]['unique_token'];
                    $path = $data[$type][0]['qr_path'];

                    $newData = [
                        'museum_id' => $item->museum_id,
                        'purchased_item_id' => $item->id,
                        'item_relation_id' => $item->item_relation_id,
                        'token' => $token,
                        'ticket_token' => Carbon::now()->secondsSinceMidnight().rand(1000, 9999),
                        'path' => $path,
                        'type' => $type,
                        'price' => $priceOneTicket,
                        'created_at' => now(),
                        'updated_at' => now(),

                    ];
                    $addedItemsToken[]=$token;
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
            return TicketQr::whereIn('token', $addedItemsToken)->get();

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
