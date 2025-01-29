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

        $unusedSubTypes = [
          'guide_price_am',
          'guide_price_other',
        ];


        $hasTicket='';
        $allPurchases = PurchasedItem::where('purchase_id', $purchaseId)->get();

        $purchasItemForOtherService = $allPurchases[0];

        if($purchasItemForOtherService->type=="other_service" && !$purchasItemForOtherService->other_service->ticket ){

          array_push($unusedTypes,'other_service');
        }
        // dd($unusedTypes);
        try {
            DB::beginTransaction();

                // $allPurchasesForQr = PurchasedItem::where('purchase_id', $purchaseId)
                // ->whereNotIn('type', $unusedTypes)
                // ->where(function ($query) use ($unusedSubTypes) {
                //   $query->whereNotIn('sub_type', $unusedSubTypes)
                //     ->orWhereNull('sub_type');
                // })
                // ->get();


//==== in $allPurchasesForQr array we gethering all purchase items except guide, for guide there is no need generate qr token
                $allPurchasesForQr=[];
                // dd($allPurchases);
                foreach($allPurchases as $item){

                  if(!in_array($item->type,$unusedTypes)){

                    if($item->partner_relation_sub_type==null){


                      if(!in_array($item->sub_type,$unusedSubTypes)){


                        array_push($allPurchasesForQr,$item);

                      }

                    }
                    else if($item->partner_relation_sub_type!="guide_price_am" && $item->partner_relation_sub_type!="guide_price_other"){

                      if(in_array($item->sub_type,['event', 'event-config'])){

                        array_push($allPurchasesForQr,$item);
                      }

                    }



                  }
                }
                // dd($allPurchasesForQr);

// ============creating $purchasesKeys array for getting qr tocken for every ticket type
            $purchasesKeys = [];

// dd($allPurchasesForQr);
            foreach ($allPurchasesForQr as $key => $item) {

                $purchasesKeys[$item->type] = array_key_exists($item->type, $purchasesKeys)
                ? $purchasesKeys[$item->type] + $item->quantity
                : $item->quantity;

            }
            // dd($purchasesKeys);


            if(isset($purchasesKeys['school'])){
              $purchasesKeys['school']=1;
            }
            if(isset($purchasesKeys['other_service'])){
              $purchasesKeys['other_service']=1;
            }
            // dd($purchasesKeys);
          //  =========  $data return us array with ticket types keys and every key has array with  "unique_token", "qr_path"
            $data = $this->getReqQrToken($url, $purchasesKeys);
// dd($data);
            $addedItemsToken = [];
// dd($allPurchases);
            foreach ($allPurchases as $key => $item) {
                $quantity = $item->quantity;

                $priceOneTicket = (int) $item->total_price / (int) $item->quantity;

                  if( $item->type == "school" ||
                      $item->type == "educational" ||
                      $item->type == "other_service" ||
                      ($item->type == "partner" && $item->sub_type == "educational" )
                      || $item->type == "product"
                    ){

                      $quantity=1;
                      $priceOneTicket=$item->total_price;

                  }

                for ($i = 0; $i < $quantity; $i++) {
                    $type = $item->type;
                    $token = $data[$type][0]['unique_token'] ?? null ;
                    $path = $data[$type][0]['qr_path'] ?? null;

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

                    if(isset($data[$type])){
                      array_shift($data[$type]);
                    }


                }
            }
// dd($allData);
// dd($addedItemsToken);
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
