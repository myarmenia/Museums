<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\EducationalProgram;
use App\Models\Event;
use App\Models\Museum;
use App\Models\Purchase;
use App\Models\PurchasedItem;
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
        $guids = $purchaseItem->where('type', 'guide');
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
                $itemDescription = Event::with('item_translations')->find($qrs[0]->item_relation_id);
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
            if(($qr['type'] == 'standart' || $qr['type'] == 'discount' || $qr['type'] == 'free') && $guids->count() > 0){
                $purchaseGuid = [];
                foreach ($guids as $guid) {
                    $purchaseGuid[] = $guid['total_price'];
                }
            }else {
                $purchaseGuid = false; 
            }

            $data['data'][] = [
                'token' => $qr['id'].mt_rand(10000, 99999),
                'photo' => public_path(Storage::url($qr['path'])),
                'description_educational_programming' => $itemDescriptionName? trim($itemDescriptionName)  : null,
                'type' => $qr['type'],
                'guid' => $purchaseGuid? $purchaseGuid : false,
                'price' => $qr['price'],
                'created_at' => $qr['created_at'],
            ];
        }

        $pdf = Pdf::loadView('components.ticket-print', ['tickets' => $data])->setPaper([0, 0, 300, 600], 'portrait');
        
        $fileName = 'ticket-' . time() . '.pdf';

        Storage::put('public/pdf-file/' . $fileName, $pdf->output());

        return asset('storage/' .  'pdf-file/' . $fileName);

        }
}
