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
        // dd($allPurchases);
        $purchasItemForOtherService = $allPurchases[0];

        if($purchasItemForOtherService->type=="other_service" && !$purchasItemForOtherService->other_service->ticket ){

          array_push($unusedTypes,'other_service');
        }
        try {
            DB::beginTransaction();

                // $allPurchasesForQr = PurchasedItem::where('purchase_id', $purchaseId)
                // ->whereNotIn('type', $unusedTypes)
                // ->where(function ($query) use ($unusedSubTypes) {
                //   $query->whereNotIn('sub_type', $unusedSubTypes)
                //     ->orWhereNull('sub_type');
                // })
                // ->get();


                // $allPurchasesForQr = PurchasedItem::query()
                // ->where('purchase_id', $purchaseId)
                // ->whereNotIn('type', $unusedTypes)
                // ->where(function ($query) use($unusedSubTypes) {
                //     $query->whereNull('sub_type')
                //           ->orWhereNotIn('sub_type', $unusedSubTypes);
                // })
                // ->where(function ($query) use($unusedSubTypes) {
                //     $query->whereNotIn('sub_type', ['event', 'event-config'])
                //           ->orWhere(function ($query) use($unusedSubTypes) {
                //               $query->whereIn('sub_type', ['event', 'event-config'])
                //                     ->where(function ($query) use ($unusedSubTypes) {
                //                         $query->whereNull('partner_relation_sub_type')
                //                               ->orWhereNotIn('partner_relation_sub_type', $unusedSubTypes);
                //                     });
                //           });
                // })
                // ->get();
                $allPurchasesForQr=[];
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


            $purchasesKeys = [];
        

            foreach ($allPurchasesForQr as $key => $item) {


                $purchasesKeys[$item->type] = array_key_exists($item->type, $purchasesKeys)
                ? $purchasesKeys[$item->type] + $item->quantity
                : $item->quantity;




            }


            if(isset($purchasesKeys['school'])){
              $purchasesKeys['school']=1;
            }
          //  dd($purchasesKeys);
            $data = $this->getReqQrToken($url, $purchasesKeys);

            $addedItemsToken = [];

            foreach ($allPurchases as $key => $item) {
                $quantity = $item->quantity;

                $priceOneTicket = (int) $item->total_price / (int) $item->quantity;

                  if($item->type == "school"|| $item->type == "educational" || ($item->type == "partner" && $item->sub_type == "educational" )){

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
