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
      // dd($request->all());


      DB::beginTransaction();
      session(['open_tab' =>'navs-top-event']);

      $requestData = $request->input('event');
      $museumId = getAuthMuseumId();
      $eventKeys = array_keys($requestData);

      $allNull = false;

        foreach ($requestData as $value) {


            if ((isset($value['standart']) && !is_null($value['standart'])) || (isset($value['discount']) && !is_null($value['discount'])) ||(isset($value['free']) && !is_null($value['free']) )) {
                $allNull = true;
                break;
            }
        }

      if (!$allNull && is_null($request->guide_price_am) && is_null($request->guide_price_other) ) {
        session([
          'errorMessage' => 'Լրացրեք քանակ դաշտը',

        ]);

        DB::rollBack();
        return redirect()->back();
      }
      if ($request->input('style') == 'basic') {  //for event with event_configs

        $allEventConfig = EventConfig::where('status', 1)->whereIn('id', $eventKeys)->get();

        //get event id
        $eventId = array_keys($allEventConfig->keyBy('event_id')->toArray());

        //check our museum has this event
        if (Event::where(['museum_id' => $museumId, 'status' => 1, 'id' => $eventId])->first()) {
          $data['purchase_type'] = 'offline';
          $data['status'] = 1;
          $data['items'] = [];
          if(isset($request->partner_id)){
            $data['partner_id'] = $request->partner_id;
          }


          $haveValue = false;
          $dataForUpdateEventConfigs = [];

          foreach ($requestData as $key => $item) {
            if ($item) {
              $haveValue = true;
              $event_config = $allEventConfig->find($key);
              $itemTotalSum = array_sum(array_filter($item, fn($value) => $value !== null));

              $visitorQuantity = (int) $event_config->visitors_quantity + (int) $itemTotalSum;

              if ($visitorQuantity > $event_config->visitors_quantity_limitation) {
                session(['errorMessage' => 'Տոմսերի քանակը չպետք է գերազանցի մնացած տոմսերի քանակին']);
                DB::rollBack();
                return redirect()->back();
              }

              foreach ($item as $sub_type => $ticket_quantity) {
                  if($ticket_quantity != null){
                      $data['items'][] = [
                        "type" => 'event-config',
                        "sub_type" => $sub_type,
                        "id" => $event_config->id,
                        "quantity" => (int) $ticket_quantity
                      ];
                  }

              }

              $dataForUpdateEventConfigs[$key]['event_config'] = $event_config;
              $dataForUpdateEventConfigs[$key]['visitor_quantity'] = $visitorQuantity;



            }
          }
          if (!$haveValue) {
            session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

            DB::rollBack();
            return redirect()->back();
          }

          if(isset($request->guide_price_am) && $request->guide_price_am != null){
              $data['items'][] = [
                "type" => 'event-config',
                "sub_type" => 'guide_price_am',
                "id" => $event_config->id,
                "quantity" => (int) $request->guide_price_am
              ];
          }
          if (isset($request->guide_price_other) && $request->guide_price_other != null) {
            $data['items'][] = [
              "type" => 'event-config',
              "sub_type" => 'guide_price_other',
              "id" => $event_config->id,
              "quantity" => (int) $request->guide_price_other
            ];
          }
// dd($data);
          $addTicketPurchase = $this->purchase($data);



          if ($addTicketPurchase) {
            foreach ($dataForUpdateEventConfigs as $config_value) {
                $config_value['event_config']->update(['visitors_quantity' => $config_value['visitor_quantity']]);
            }


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
        // dd($event);
        if ($event) {
          if (in_array(null, $requestData, true) && is_null($request->guide_price_am) && is_null($request->guide_price_other) ) {
            session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

            DB::rollBack();
            return redirect()->back();
          }

          $requestDataValue = array_values($requestData)[0];
          $data['purchase_type'] = 'offline';
          $data['status'] = 1;
          if(isset($request->partner_id)){
            $data['partner_id'] = $request->partner_id;
          }


          foreach ($requestDataValue as $sub_type => $ticket_quantity) {
            if ($ticket_quantity != null) {
              $data['items'][] = [
                "type" => 'event',
                "sub_type" => $sub_type,
                "id" => $event->id,
                "quantity" => (int) $ticket_quantity
              ];
            }

          }

          if (isset($request->guide_price_am) && $request->guide_price_am != null) {
            $data['items'][] = [
              "type" => 'event',
              "sub_type" => 'guide_price_am',
              "id" => $event->id,
              "quantity" => (int) $request->guide_price_am
            ];
          }
          if (isset($request->guide_price_other) && $request->guide_price_other != null) {
            $data['items'][] = [
              "type" => 'event',
              "sub_type" => 'guide_price_other',
              "id" => $event->id,
              "quantity" => (int) $request->guide_price_other
            ];
          }


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
