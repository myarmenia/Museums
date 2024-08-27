<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventConfig;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventTicket extends CashierController
{
  use PurchaseTrait;
  use QrTokenTrait;

  public function __invoke(Request $request)
  {
    try {
      DB::beginTransaction();

      $requestData = $request->input('event');
dd($requestData);
      $museumId = getAuthMuseumId();
      $eventKeys = array_keys($requestData);

      if ($request->input('style') == 'basic') {  //for event with event_configs

        $allEventConfig = EventConfig::where('status', 1)->whereIn('id', $eventKeys)->get();

        //get event id
        $eventId = array_keys($allEventConfig->keyBy('event_id')->toArray());

        //check our museum has this event
        if (Event::where(['museum_id' => $museumId, 'status' => 1, 'id' => $eventId])->first()) {
          $data['purchase_type'] = 'offline';
          $data['status'] = 1;
          $data['items'] = [];

          $haveValue = false;

          foreach ($requestData as $key => $item) {
            if ($item) {
              $haveValue = true;
              $event = $allEventConfig->find($key);
              $visitorQuantity = (int) $event->visitors_quantity + (int) $item;

              if ($visitorQuantity > $event->visitors_quantity_limitation) {
                session(['errorMessage' => 'Տոմսերի քանակը չպետք է գերազանցի մնացած տոմսերի քանակին']);
                DB::rollBack();
                return redirect()->back();
              }

              $data['items'][] = [
                "type" => 'event-config',
                "id" => $event->id,
                "quantity" => (int) $item
              ];


            }
          }
          if (!$haveValue) {
            session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

            DB::rollBack();
            return redirect()->back();
          }

          $addTicketPurchase = $this->purchase($data);

          $event->update(['visitors_quantity' => $visitorQuantity]);

          if ($addTicketPurchase) {
            $addQr = $this->getTokenQr($addTicketPurchase->id);

            if ($addQr) {
              $pdfPath = $this->showReadyPdf($addTicketPurchase->id);
              session(['success' => 'Տոմսերը ավելացված է']);

              DB::commit();
              return redirect()->back()->with('pdfFile', $pdfPath);
            }
          }
        }
      } else {  //for event temporary
        $event = Event::where(['museum_id' => $museumId, 'status' => 1, 'id' => $eventKeys])->first();
        if ($event) {

          if (in_array(null, $requestData, true)) {
            session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

            DB::rollBack();
            return redirect()->back();
          }

          $quantity = $value = array_values($requestData)[0];

          $data['purchase_type'] = 'offline';
          $data['status'] = 1;
          $data['items'][] = [
            "type" => 'event',
            "id" => $event->id,
            "quantity" => (int) $quantity
          ];

          $addTicketPurchase = $this->purchase($data);

          if ($addTicketPurchase) {
            $addQr = $this->getTokenQr($addTicketPurchase->id);

            if ($addQr) {
              $pdfPath = $this->showReadyPdf($addTicketPurchase->id);
              session(['success' => 'Տոմսերը ավելացված է']);

              DB::commit();
              return redirect()->back()->with('pdfFile', $pdfPath);
            }
          }

        }

      }

      session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
      DB::rollBack();
      return redirect()->back();

    } catch (\Exception $e) {
      session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
      DB::rollBack();
      dd($e->getMessage());
      return false;
    } catch (\Error $e) {
      session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
      DB::rollBack();
      dd($e->getMessage());
      return false;
    }
  }
}
