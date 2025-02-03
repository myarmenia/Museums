<?php

namespace App\Services\Ticket;

use App\Models\EventConfig;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\TicketQr;
use App\Traits\Hdm\ReturnHdmTrait;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnTicketService
{
  use ReturnHdmTrait;
  public function checkToken($token)
  {

    if (!ctype_digit($token)) {
      return ['success' => false, 'type' => null, 'message' => 'Թոքենը պետք է լինի բաղկացած միայն թվերից։'];
    }

    $token = (int) $token;
    $ticket = null;
    $currentDate = Carbon::now();

    $museumId = getAuthMuseumId();

    if ($museumId && $token) {

      $ticket = $this->getActiveTicket($token, $museumId);

      if ($ticket && $ticket->type == "united") {
        return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տոմսը միասնական է որը վերադարձի ենթակա չէ։'];
      }

      if ($ticket && $ticket->visited_date != null) {
        return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով մուտք արդեն եղել է։'];
      }
// dd($ticket);
      if ($ticket) {
        $guides = false;
        if ($ticket->type == "free" || $ticket->type == "discount" || $ticket->type == "standart") {
          $purchaseItemId = $ticket->purchased_item_id;
          $purchaseId = PurchasedItem::where('id', $purchaseItemId)->first()->purchase_id;
          $guides = PurchasedItem::where(['purchase_id' => $purchaseId, 'type' => 'guide', 'returned_quantity' => 0])->count();

          if ($guides) {
            $guides = true;
          }
        }
        if($ticket->type == 'event-config'){
            if($ticket->event_config == null){
              return ['success' => false, 'type' => null, 'message' => 'Ինչ որ բան այն չէ։'];
            }

            $ticketDate = $ticket->event_config->day;
            if($ticketDate < $currentDate && $ticketDate != null){
                return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տոմսի միջոցառման ժամկետն ավարտվել է։'];
            }
        }

        if ($ticket->type == 'event') {
          if ($ticket->event == null) {
            return ['success' => false, 'type' => null, 'message' => 'Ինչ որ բան այն չէ։'];
          }

          $ticketDate = $ticket->event->end_date;
          if ($ticketDate < $currentDate && $ticketDate !=null) {
            return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տոմսի ցուցադրության ժամկետն ավարտվել է։'];
          }
        }


        return ['success' => true, 'guides' => $guides, 'type' => getTranslateTicketTitl($ticket->type)];
      }

      return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տոմս չի գտնվել կամ տոմսն օգտագործված է։'];
    }

    return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տոմս չի գտնվել։'];

  }

  public function removeToken($data)
  {
// dd($data);
    $data = json_decode($data['json'], true);

    $token = $data['dataId'];

    $museumId = getAuthMuseumId();

    if ($museumId && $token) {
      try {
        DB::beginTransaction();


        $ticket = $this->getActiveTicket($token, $museumId);
// dd($ticket);

        if ($ticket && $ticket->visited_date != null) {
          return ['success' => false, 'message' => 'Տվյալ թոքենով մուտք արդեն եղել է։'];
        }

        if ($ticket == null) {
          return ['success' => false, 'message' => 'Տվյալ թոքենով մուտք արդեն եղել է։'];
        }

        if($data['dataId']){
          $purchaseReturnedAmount = 0;
          $ticket->update(['status' => TicketQr::STATUS_RETURNED]);
          $purchasedItemId = $ticket->purchased_item_id;

          $purchaseItem = PurchasedItem::where('id', $purchasedItemId)->first();
// dd( $purchaseItem);
          $purchaseQunatity = $purchaseItem->quantity;
          $purchasePrice = $purchaseItem->total_price;
          if ($purchaseItem->type == "free") {
            $totalPrice = 0;
            $oneTicketPrice = 0;
          }
           else {



            $oneTicketPrice = (int) $purchasePrice / (int) $purchaseQunatity;
            $totalPrice = (int) $purchaseItem->returned_total_price + $oneTicketPrice;

          }
          if($purchaseItem->type == "event-config" && $purchaseItem->sub_type !='guide_price_am' && $purchaseItem->sub_type !='guide_price_other'){
            $eventConfig =  EventConfig::where('id', $purchaseItem->item_relation_id)->first();
            $countVisitors = (int) $eventConfig->visitors_quantity - 1;
            $eventConfig->update(['visitors_quantity' => $countVisitors]);
          }

          if ($purchaseItem->type == "educational") {

                $returnedQuantity = $purchaseItem->quantity;
                $totalPrice = $purchaseItem->total_price;
                $purchaseNewAmount = $totalPrice;

          }
          else if ($purchaseItem->type == "school") {

            $returnedQuantity = $purchaseItem->quantity;
            $totalPrice = $purchaseItem->total_price;
            $purchaseNewAmount =$purchaseItem->purchase->returned_amount + $totalPrice;

          }
          else if ($purchaseItem->type == "partner" &&  $purchaseItem->sub_type=="educational") {
              $returnedQuantity = $purchaseItem->quantity;
              $totalPrice = $purchaseItem->total_price;
              $purchaseNewAmount =$purchaseItem->purchase->returned_amount + $totalPrice;


          }
          else if ( $purchaseItem->type == "product" ) {
            $product = Product::where('id',$purchaseItem->item_relation_id)->first();
            $returnedQuantity = $purchaseItem->quantity;
            $totalPrice = $purchaseItem->total_price;
            $purchaseNewAmount = $purchaseItem->purchase->returned_amount + $totalPrice;
            $after_returned_total_product_count = $product->quantity + $returnedQuantity;
            $product->update(['quantity' => $after_returned_total_product_count]);
            
        }
          else{
              $returnedQuantity = $purchaseItem->returned_quantity + 1;
              $purchaseNewAmount = $purchaseItem->purchase->returned_amount + $oneTicketPrice;
          }

          $purchaseItem->update(['returned_quantity' => $returnedQuantity, 'returned_total_price' => $totalPrice]);
          $purchaseItem->purchase->update(['returned_amount' => $purchaseNewAmount]);

          // =============================
          $incalculableTypes = ['partner', 'school', 'free'];
          if (museumHasHdm() && !in_array($purchaseItem->type, $incalculableTypes) ) {

            $print = $this->returnHdm($purchasedItemId);

            if (!$print['success']) {

                $message = isset($print['result']['message']) ? $print['result']['message'] : 'ՀԴՄ սարքի խնդիր';

                session(['errorMessage' => $message]);
                return redirect()->back();
            }

          }

          // ====================================

        }


        DB::commit();
        if ($ticket) {
          return ['success' => true];
        }

      } catch (\Exception $e) {
        session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        DB::rollBack();
        dd($e->getMessage());
        return ['success' => false];
      } catch (\Error $e) {
        session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        DB::rollBack();
        dd($e->getMessage());
        return ['success' => false];
      }
    }
  }

  private function getActiveTicket(int $token, int $museumId): ?TicketQr
  {
    $dateLimit = Carbon::now()->subDays(15);

    return TicketQr::where('ticket_token', $token)
      ->where('status', TicketQr::STATUS_ACTIVE)
      ->where('created_at', '>=', $dateLimit)
      ->where(function ($query) use ($museumId) {
        $query->where('type', 'united')
          ->orWhere('museum_id', $museumId);
      })
      ->first();
  }
}
