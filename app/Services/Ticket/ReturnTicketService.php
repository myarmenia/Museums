<?php

namespace App\Services\Ticket;

use App\Models\PurchasedItem;
use App\Models\TicketQr;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReturnTicketService
{
  public function checkToken($token)
  {

    if(!ctype_digit($token)){
      return ['success' => false, 'type' => null, 'message' => 'Թոքենը պետք է լինի բաղկացած միայն թվերից։'];
    }

    $token = (int) $token;
    $ticket = null;

    $museumId = getAuthMuseumId();

    if ($museumId && $token) {

      $ticket = $this->getActiveTicket($token, $museumId);

      if($ticket && $ticket->type == "united"){
         return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տոմսը միասնական է որը վերադարձի ենթակա չէ։'];
      }

      if ($ticket) {
        return ['success' => true, 'type' => getTranslateTicketTitl($ticket->type)];
      }
    }

    return ['success' => false, 'type' => null, 'message' => 'Տվյալ թոքենով տվյալ չի գտնվել։'];

  }

  public function removeToken($token)
  {
    // $id = $this->extractIdFromToken($token);

    $museumId = getAuthMuseumId();

    if ($museumId && $token) {
      try {
        DB::beginTransaction();

        $ticket = $this->getActiveTicket($token, $museumId);

        $ticket->update(['status' => TicketQr::STATUS_RETURNED]);

        $purchasedItemId = $ticket->purchased_item_id;
        $purchaseItem = PurchasedItem::where('id', $purchasedItemId)->first();
        $purchaseQunatity = $purchaseItem->quantity;
        $purchasePrice = $purchaseItem->total_price;

        if($purchaseItem->type == "free"){
          $totalPrice = 0;
        } else {
          $oneTicketPrice = (int) $purchasePrice / (int) $purchaseQunatity;
          $totalPrice = $purchasePrice - $oneTicketPrice;
        }

        $purchaseItem->update(['quantity' => $purchaseQunatity-1, 'total_price' => $totalPrice]);
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

  // private function extractIdFromToken(string $token): ?int
  // {
  //   $rawId = substr($token, 0, -5);
  //   return is_numeric($rawId) ? (int) $rawId : null;
  // }

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
