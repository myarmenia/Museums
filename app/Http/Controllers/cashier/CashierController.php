<?php

namespace App\Http\Controllers\cashier;

use Storage;
use App\Models\Event;
use App\Models\Museum;
use App\Models\Purchase;
use App\Models\TicketQr;
use App\Models\TicketPdf;
use App\Models\EventConfig;
use Illuminate\Http\Request;
use App\Models\PurchasedItem;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\EducationalProgram;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;



class CashierController extends Controller
{
    public function showReadyPdf(int $purchaseId, $ticketQrs = null)
    {
// dd($purchaseId);
        $purchaseData = Purchase::with('purchased_items.ticketQr')->where('id', $purchaseId)->first();
        $museumId = $purchaseData->museum_id;
        $museum = Museum::with('translations')->find($museumId);

        $purchaseItem = PurchasedItem::where('purchase_id', $purchaseId)->get();

        $purchaseItemIds = $purchaseItem->pluck('id');
        $guids = $purchaseItem->where('type','guide')->where('returned_quntity', 0);
        $event_guids = $purchaseItem->where('returned_quntity', 0)
                    ->whereIn('sub_type', ['guide_price_am', 'guide_price_other']);


        $itemDescription = null;
        $itemDescriptionName = '';
        $itemDescriptionName_en = '';
        $museumTranslation = $museum->translations->keyBy('lang');

        $data = [
            'museum_name' => [
                'am' => $museumTranslation['am']->name,
                'ru' => $museumTranslation['ru']->name,
                'en' => $museumTranslation['en']->name,
            ],
        ];

        if($ticketQrs){

            $qrs = $ticketQrs;
        }else{
            $qrs = TicketQr::whereIn('purchased_item_id', $purchaseItemIds)->get();

        }


        if($qrs[0]->type == 'event' || $qrs[0]->type == 'event-config' ){
            if($qrs[0]->type == 'event-config'){
                $eventConfig = EventConfig::with('event.item_translations')->find($qrs[0]->item_relation_id);
                $eventAllConfigs = EventConfig::where('event_id', $eventConfig->event->id)->get();
                $itemDescription = $eventConfig->event;

            }elseif ($qrs[0]->type == 'event') {
                $event = Event::where('id', $qrs[0]->item_relation_id)->get();
                $itemDescription = Event::with('item_translations')->find($qrs[0]->item_relation_id);

            }elseif ($qrs[0]->type == 'educational') {
                $itemDescription = EducationalProgram::with('item_translations')->find($qrs[0]->item_relation_id);
            }else{
                return false;
            }
            $desc = $itemDescription->item_translations->where('lang', 'am')->first()->name;
            $desc_en = $itemDescription->item_translations->where('lang', 'en')->first()->name;
            $wordArr = explode(" ", $desc);
            foreach ($wordArr as $key => $word) {
               if($word){
                   if($key < 6){
                       $itemDescriptionName .= $word.' ';
                   }
                   else{
                       break;
                   }
               }
            }
            $wordArr_en = explode(" ", $desc_en);
            foreach ($wordArr_en as $key_en => $word_en) {
               if($word_en){
                   if($key_en < 6){
                       $itemDescriptionName_en .= $word_en.' ';
                   }
                   else{
                       break;
                   }
               }
            }


        }


        foreach ($qrs as $key=>$qr) {

          if($qr->type="educational"){


            $itemDescription = EducationalProgram::with('item_translations')->find($qr->item_relation_id);

            $desc = $itemDescription->item_translations->where('lang', 'am')->first()->name;

            $desc_en = $itemDescription->item_translations->where('lang', 'en')->first()->name;
            $wordArr = explode(" ", $desc);

            foreach ($wordArr as $key1 => $word) {
              if($word){
                  if($key1 < 6){
                      $itemDescriptionName .= $word.' ';
                  }
                  else{
                      break;
                  }
              }
            }
            $wordArr_en = explode(" ", $desc_en);
            foreach ($wordArr_en as $key_en1 => $word_en) {
              if($word_en){
                  if($key_en1 < 6){
                      $itemDescriptionName_en .= $word_en.' ';
                  }
                  else{
                      break;
                  }
              }
            }
          }



            if($qr['type'] == 'event-config'){
                $configItem = $eventAllConfigs->where('id', $qr->item_relation_id)->first();

                $event_day = [
                    'day' => $configItem ->day,
                    'start' => $configItem ->start_time,
                    'end' => $configItem ->end_time,
                ];
            }

            if ($qr['type'] == 'event') {
              $configItem = $event->where('id', $qr->item_relation_id)->first();

              $event_day = [
                'start' => $configItem->start_date,
                'end' => $configItem->end_date,
              ];
            }

            if(($qr['type'] == 'standart' || $qr['type'] == 'discount' || $qr['type'] == 'free') && $guids->count() > 0){
                $purchaseGuid = [];
                foreach ($guids as $guid) {
                    $purchaseGuid[] = $guid['total_price'];
                }
            }elseif($qr['type'] == 'event' || $qr['type'] == 'event-config'){
                $purchaseGuid = [];
                foreach ($event_guids as $guid) {
                  $purchaseGuid[] = $guid['total_price'];
                }
            }
            else {
                $purchaseGuid = false;
            }


            $data['data'][] = [
                'ticket_token' => $qr['ticket_token'],
                'description_educational_programming' => $itemDescriptionName? trim($itemDescriptionName)  : null,
                'description_educational_programming_en' => $itemDescriptionName_en? trim($itemDescriptionName_en)  : null,
                'action_date' => $event_day ?? "",
                'type' => $qr['type'],
                'guid' => $purchaseGuid? $purchaseGuid : false,
                'price' => $qr['price'],
                'created_at' => $qr['created_at'],
                'sub_type' => $qr->purchased_item->sub_type
            ];
            if(!is_null($qr['path'])){

              $data['data'][$key]['photo'] = Storage::disk('local')->path($qr['path']);

            }

            if($qr['type']=="other_service"){
              $data['data'][$key]['service_name_am'] = $qr->purchased_item->other_service->translation('am')->name;
              $data['data'][$key]['service_name_en'] = $qr->purchased_item->other_service->translation('en')->name;
            }
            if($qr['type'] =="school" || $qr['type'] == "educational"){

              $data['data'][$key]['quantity']= $qr->purchased_item->quantity;

            }





        }

        $pdf = Pdf::loadView('components.ticket-print', ['tickets' => $data])->setPaper([0, 0, 300, 600], 'portrait');


        $fileName = 'ticket-' . time() . $purchaseId . '.pdf';
        $path = 'public/pdf-file/' . $fileName;

        Storage::put($path, $pdf->output());

        TicketPdf::create([
            'museum_id' => $museumId,
            'pdf_path' => $path
        ]);

        return asset('storage/' .  'pdf-file/' . $fileName);

        }
}
// ================
// ========================
