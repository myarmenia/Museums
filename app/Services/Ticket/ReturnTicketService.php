<?php

namespace App\Services\Ticket;

use App\Models\EventConfig;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\TicketQr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnTicketService
{
  public function checkToken($token)
  {

    if (!ctype_digit($token)) {
      return ['success' => false, 'type' => null, 'message' => 'Թոքենը պետք է լինի բաղկացած միայն թվերից։'];
    }

    $token = (int) $token;
    $ticket = null;

    $museumId = getAuthMuseumId();

    if ($museumId && $token) {

      $ticket = $this->getActiveTicket($token, $museumId);

      if ($ticket && $ticket->type == "united") {
        return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տոմսը միասնական է որը վերադարձի ենթակա չէ։'];
      }

      if ($ticket && ($ticket->accesses->count() > 0)) {
        return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով մուտք արդեն եղել է։'];
      }

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

        return ['success' => true, 'guides' => $guides, 'type' => getTranslateTicketTitl($ticket->type)];
      }
    }

    return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տվյալ չի գտնվել։'];

  }

  public function removeToken($data)
  {
    $data = json_decode($data['json'], true);

    $token = $data['dataId'];

    $museumId = getAuthMuseumId();

    if ($museumId && $token) {
      try {
        DB::beginTransaction();

        $ticket = $this->getActiveTicket($token, $museumId);

        if ($ticket && ($ticket->accesses->count() > 0)) {
          return ['success' => false, 'message' => 'Տվյալ թոքենով մուտք արդեն եղել է։'];
        }

        if (!$data['ticketApprove'] && !$data['guideApprove']) {
          return ['success' => false, 'message' => 'Նշեք դաշտերից մեկը'];
        }

        $removeGuid = false;

        if ($data['ticketApprove']) {
          $ticket->update(['status' => TicketQr::STATUS_RETURNED]);

          $purchasedItemId = $ticket->purchased_item_id;
          $purchaseItem = PurchasedItem::where('id', $purchasedItemId)->first();

          $purchaseQunatity = $purchaseItem->quantity;
          $purchasePrice = $purchaseItem->total_price;

          if ($purchaseItem->type == "free") {
            $totalPrice = 0;
            $oneTicketPrice = 0;
          } else {
            $oneTicketPrice = (int) $purchasePrice / (int) $purchaseQunatity;
            $totalPrice = (int) $purchaseItem->returned_total_price + $oneTicketPrice;
          }

          if($purchaseItem->type == "event"){
            $eventConfig =  EventConfig::where('id', $purchaseItem->item_relation_id)->first();
            $countVisitors = (int) $eventConfig->visitors_quantity - 1;
            $eventConfig->update(['visitors_quantity' => $countVisitors]);
          }

          $returnedQuantity = $purchaseItem->returned_quantity + 1;
          $purchaseNewAmount = $purchaseItem->purchase->returned_amount + $oneTicketPrice;

          $purchaseItem->update(['returned_quantity' => $returnedQuantity, 'returned_total_price' => $totalPrice]);
          $purchaseItem->purchase->update(['returned_amount' => $purchaseNewAmount]);

          $countPurchaseItemsForRemoveGuids = PurchasedItem::where(['purchase_id' => $purchaseItem->purchase_id, 'returned_quantity' => 0])->where('type', '!=', 'guide')->count();

          if ($countPurchaseItemsForRemoveGuids == 0) {
            $removeGuid = true;
          }

        }

        if ($data['guideApprove'] || $removeGuid) {
          $purchaseItemId = $ticket->purchased_item_id;
          $purchaseId = PurchasedItem::where('id', $purchaseItemId)->first()->purchase_id;
          $guides = PurchasedItem::where(['purchase_id' => $purchaseId, 'type' => 'guide', 'returned_quantity' => 0])->get();
          $purchaseReturnedAmount = 0;
          foreach ($guides as $key => $guid) {
            $guidQuantity = $guid->quantity;
            $guidPrice = $guid->total_price;
            $purchaseReturnedAmount += $guidPrice;
            $guid->update(['returned_quantity' => $guidQuantity, 'returned_total_price' => $guidPrice]);
          }

          $purchase = Purchase::where('id', $purchaseId)->first();
          $purchaseReadyAmount = $purchase->returned_amount + $purchaseReturnedAmount;
          $purchase->update(['returned_amount' => $purchaseReadyAmount]);
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
