<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\EducationalProgram;
use App\Models\Event;
use App\Models\EventConfig;
use App\Models\Museum;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\TicketPdf;
use App\Models\TicketQr;
use Illuminate\Http\Request;
use Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;



class CashierController extends Controller
{
    public function showReadyPdf(int $purchaseId, $ticketQrs = null)
    {
        $purchaseData = Purchase::with('purchased_items.ticketQr')->where('id', $purchaseId)->first();
        $museumId = $purchaseData->museum_id;
        $museum = Museum::with('translations')->find($museumId);

        $purchaseItem = PurchasedItem::where('purchase_id', $purchaseId)->get();
        $purchaseItemIds = $purchaseItem->pluck('id');
        $guids = $purchaseItem->where('type','guide')->where('returned_quntity', 0);

        $itemDescription = null;
        $itemDescriptionName = '';
    
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

        if($qrs[0]->type == 'event' || $qrs[0]->type == 'educational'){
            if($qrs[0]->type == 'event'){
                $eventConfig = EventConfig::with('event.item_translations')->find($qrs[0]->item_relation_id);
                $eventAllConfigs = EventConfig::where('event_id', $eventConfig->event->id)->get();
                $itemDescription = $eventConfig->event;

            }elseif ($qrs[0]->type == 'educational') {
                $itemDescription = EducationalProgram::with('item_translations')->find($qrs[0]->item_relation_id);
            }else{
                return false;
            }
            $desc = $itemDescription->item_translations->where('lang', 'am')->first()->name;

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

        }


        foreach ($qrs as $qr) {
            
            if($qr['type'] == 'event'){
                $configItem = $eventAllConfigs->where('id', $qr->item_relation_id)->first();

                $event_day = [
                    'day' => $configItem ->day,
                    'start' => $configItem ->start_time,
                    'end' => $configItem ->end_time,
                ];
            }

            if(($qr['type'] == 'standart' || $qr['type'] == 'discount' || $qr['type'] == 'free') && $guids->count() > 0){
                $purchaseGuid = [];
                foreach ($guids as $guid) {
                    $purchaseGuid[] = $guid['total_price'];
                }
            }else {
                $purchaseGuid = false; 
            }

            $data['data'][] = [
                'ticket_token' => $qr['ticket_token'],
                'photo' => public_path(Storage::url($qr['path'])),
                'description_educational_programming' => $itemDescriptionName? trim($itemDescriptionName)  : null,
                'action_date' => $event_day ?? "",
                'type' => $qr['type'],
                'guid' => $purchaseGuid? $purchaseGuid : false,
                'price' => $qr['price'],
                'created_at' => $qr['created_at'],
            ];
        }

        $pdf = Pdf::loadView('components.ticket-print', ['tickets' => $data])->setPaper([0, 0, 300, 600], 'portrait');
        
        $fileName = 'ticket-' . time() . '.pdf';
        $path = 'public/pdf-file/' . $fileName;

        Storage::put($path, $pdf->output());

        TicketPdf::create([
            'museum_id' => $museumId,
            'pdf_path' => $path
        ]);

        return asset('storage/' .  'pdf-file/' . $fileName);

        }
}
