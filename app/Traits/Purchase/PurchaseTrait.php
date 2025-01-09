<?php
namespace App\Traits\Purchase;

use App\Models\CorporativeSale;
use App\Models\EducationalProgram;
use App\Models\Event;
use App\Models\EventConfig;
use App\Models\GuideService;
use App\Models\Museum;
use App\Models\OtherService;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Models\PurchaseUnitedTickets;
use App\Models\Ticket;
use App\Models\TicketSchoolSetting;
use App\Models\TicketSubscriptionSetting;
use App\Models\TicketUnitedSetting;
use Illuminate\Support\Facades\Auth;

trait PurchaseTrait
{
  private $typeAtTimeOfSale = 'online';

  public function purchase($data)
  {

    $this->typeAtTimeOfSale = $data['purchase_type'];
    $museum_id = null;
    $purchase_data = [];

    if (auth('api')->user() != null) {
      $user = auth('api')->user();

      $purchase_data['user_id'] = $user->id;
      $purchase_data['email'] = $user->email;
      $purchase_data['age'] = $user->birth_date ? getAge($user->birth_date) : null;

    }

    if (Auth::user() != null) {
      $user = Auth::user();

      $purchase_data['user_id'] = $user->id;
      $purchase_data['email'] = $user->email;
    }

    if (isset($data['person']) && count($data['person']) > 0) {

      $country_id = isset($data['person']['country_id']) ? getCountry($data['person']['country_id'])->id : null;
      $data['person']['country_id'] = $country_id;
      $person = $this->createPerson($data['person']);
      $purchase_data['email'] = $person->email;
      $purchase_data['age'] = $person->age;
      $purchase_data['person_purchase_id'] = $person->id;
    }

    $purchase_data['type'] = $data['purchase_type'];
    $purchase_data['amount'] = 0;
    $purchase_data['status'] = isset($data['status']) ? $data['status'] : 0;

    // ======== when quantity = 0 ================
    $find_quantity_0 = array_filter($data['items'], function ($value) {
      return $value['quantity'] == 0;
    });


    if (count($find_quantity_0) > 0) {
      return ['error' => 'system_error'];
    }
    // =============================================


    // ======== when selected anavalible id from museum ================
    if (!$this->getAllMuseumsTikets($data['items'])) {
      return ['error' => 'system_error'];
    }
    // ========================================================

    $purchase = Purchase::create($purchase_data);

    if(isset($data['comment']) && $data['comment'] != null){
      $purchase->cashier_comment()->create($data['comment']);
    }

    $data['purchase_id'] = $purchase->id;

    $item = $this->itemStore($data);


    if (isset($item['error'])) {

        $purchase->purchased_items->each(function ($item) {
            $item->delete();
        });
        $purchase->delete();
        return $item;
    }

    $museum_id = $item && $item->museum_id != null ? $item->museum_id : null;

    if (isset($data['request_type']) && $data['request_type'] == 'cart') {
      $museum_id = null;           // museum_id ete cartic lini gnum@ petq e toghne null
    }

    $prcase_items_count = $purchase->purchased_items->count();

    if ($prcase_items_count == 0) {
      return ['error' => 'system_error'];
    }

    $amount = array_sum($purchase->purchased_items->pluck('total_price')->toArray());
    $update = $purchase->update(['amount' => $amount, 'museum_id' => $museum_id]);

    return $update ? $purchase : ['error' => 'system_error'];
  }

  public function itemStore(array $data)
  {

    foreach ($data['items'] as $key => $value) {

      $value['purchase_id'] = $data['purchase_id'];

      //  ===================== product ==============================

      if ($value['type'] == 'product') {

        $maked_data = $this->makeProductData($value);
        unset($maked_data['product_id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);
        } else {
          $row = ['error' => 'product_not_available'];
          break;
        }

      }

      //  ============================================================

      //  ===================== event-config ================================


      if ($value['type'] == 'event-config') {

        // $summedQuantities = collect($data['items'])
        //   ->groupBy(function ($q_item) {
        //     return $q_item['type'] . '-' . $q_item['id'];
        //   }) // Группировка
        //   ->map(function ($group) {
        //     return $group->sum('quantity');
        //   }) // Суммирование
        //   ->sum();
        // dump($value);

        $summedQuantities = collect($data['items'])
          ->filter(function ($item) use ($value) {
            // Фильтруем элементы:
            // - type должен совпадать с $value['type'] ('event-config')
            // - id должен совпадать с $value['id']
            // - sub_type не должен быть 'guid_price_am' или 'guid_price_other'
            return $item['type'] === $value['type'] &&
              $item['id'] === $value['id'] &&
              !in_array($item['sub_type'], ['guide_price_am', 'guide_price_other']);
          })
          ->groupBy(function ($q_item) {
            return $q_item['type'] . '-' . $q_item['id'];
          }) // Группируем оставшиеся элементы
          ->map(function ($group) {
            // Считаем сумму 'quantity' для каждой группы
            return $group->sum('quantity');
          })
          ->sum(); // Суммируем все группы


        $maked_data = $this->makeEventConfigData($value, $summedQuantities);
        unset($maked_data['id']);


        if ($maked_data) {
          if(isset($data['partner_id'])){
            $ec_type = $maked_data['type'];   // event config type
            $ec_sub_type = $maked_data['sub_type']; // event config sub_type

            $maked_data['item_relation_id'] = $data['partner_id'];
            $maked_data['partner_id'] = $data['partner_id'];
            $maked_data['type'] = 'partner';
            $maked_data['sub_type'] = $ec_type;
            $maked_data['partner_relation_id'] =  $value['id'];
            $maked_data['partner_relation_sub_type'] = $ec_sub_type;

          }

          $row = $this->addItemInPurchasedItem($maked_data);
        } else {
          $event = $this->getAvailableEventViaEventConfig($value['id']);

          $row = ['error' => 'ticket_not_available', 'name' => $event];
          break;
        }

      }
      //  ===============================================================================

      //  ===================== event ================================


      if ($value['type'] == 'event') {

        $maked_data = $this->makeEventData($value);
        unset($maked_data['id']);

        if ($maked_data) {
          if (isset($data['partner_id'])) {

            $e_type = $maked_data['type'];   // event type
            $e_sub_type = $maked_data['sub_type']; // event sub_type

            $maked_data['item_relation_id'] = $data['partner_id'];
            $maked_data['partner_id'] = $data['partner_id'];
            $maked_data['type'] = 'partner';
            $maked_data['sub_type'] = $e_type;
            $maked_data['partner_relation_id'] = $value['id'];
            $maked_data['partner_relation_sub_type'] = $e_sub_type;

          }


          $row = $this->addItemInPurchasedItem($maked_data);
        } else {

          $row = ['error' => 'undefined_event', 'name' => ''];
          break;
        }

      }
      //  ===============================================================================

      //  ========= standart ======== discount ========= free ======= subscription ======


      if ($value['type'] == 'standart' || $value['type'] == 'discount' || $value['type'] == 'free' || $value['type'] == 'subscription') {

        $maked_data = $this->makeTicketData($value);
        unset($maked_data['id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }

      //  ===============================================================================

      //  =================== school ====================================================


      if ($value['type'] == 'school') {

        $maked_data = $this->makeSchoolTicketData($value);
        unset($maked_data['id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }

      //  ===============================================================================

      //  =================== guide_am ================= guide_other ====================


      if ($value['type'] == 'guide_am' || $value['type'] == 'guide_other' ) {

        $maked_data = $this->makeGuideData($value);
        unset($maked_data['id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }
      //  ==================================================================

      //  ===================== educational ================================

      if ($value['type'] == 'educational') {

        $maked_data = $this->makeEducationalData($value);
        unset($maked_data['id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }
      // ==============================================================

      //  ===================== corporative ================================

      if ($value['type'] == 'corporative') {

        $maked_data = $this->makeCorporativeData($value);
        unset($maked_data['id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }
      // ==============================================================

      //  ===================== united ================================

      if ($value['type'] == 'united') {

        $maked_data = $this->makeUnitedTicketData($value);

        if ($maked_data) {
          $row = $this->createUnitedTickets($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }

      // =================================================================

      //  =================== other service ===============================

      if ($value['type'] == 'other_service') {

        $maked_data = $this->makeOtherServiceData($value);
        unset($maked_data['id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }

      //  ===============================================================================

      //  =================== partner ====================================================


      if ($value['type'] == 'partner') {

        $maked_data = $this->makePartnerTicketData($value);
        unset($maked_data['id'], $maked_data['educational_id']);

        if ($maked_data) {
          $row = $this->addItemInPurchasedItem($maked_data);

        } else {
          $row = ['error' => 'ticket_not_available'];
          break;
        }

      }

      //  ===============================================================================


    }

    return $row;

  }

  public function makeProductData($data)
  {
    $product = $this->getProduct($data['product_id'], $data['quantity']);

    if (!$product) {
      return false;
    }

    $data['museum_id'] = $product->museum->id;

    $total_price = $product->price * $data['quantity'];

    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['product_id'];

    return $data;
  }
  public function makeTicketData($data)
  {
    $ticket = $data['type'] == 'subscription' ? $this->getSubscriptionTicket($data['id']) : $this->getStandartTicket($data['id']);

    if (!$ticket) {
      return false;
    }

    $data['museum_id'] = $ticket ? $ticket->museum->id : false;


    $coefficient = ticketType($data['type'])->coefficient;
    $total_price = $ticket->price * $coefficient * $data['quantity'];

    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;
  }

  public function makeSchoolTicketData($data)
  {
    $school_ticket = $this->getSchoolTicket($data['id']);

    if (!$school_ticket) {
      return false;
    }

    $muyseum_id = museumAccessId();

    if (!$muyseum_id) {
      return false;
    }

    $data['museum_id'] = $muyseum_id;

    $total_price = $school_ticket->price * $data['quantity'];

    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;
  }

  public function makeGuideData($data)
  {

    $type = $data['type'] == 'guide_am' ? 'price_am' : 'price_other';
    $guide = $this->getGuide($data['id']);

    if (!$guide) {
      return false;
    }

    $data['museum_id'] = $guide ? $guide->museum->id : false;

    $total_price = $guide[$type] * $data['quantity'];

    $data['type'] = 'guide';
    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;
  }

  public function makeEducationalData($data){

    $educational_program = $this->getEducationalProgram($data['id']);

    if (!$educational_program) {
      return false;
    }

    $data['museum_id'] = $educational_program ? $educational_program->museum->id : false;

    $total_price = $educational_program->price * $data['quantity'];

    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;

  }

  public function makeCorporativeData($data){
    $corporative = $this->getCorporative($data['id']);

    if (!$corporative) {
      return false;
    }

    $data['museum_id'] = $corporative ? $corporative->museum->id : false;

    $total_price = $corporative->price * $data['quantity'];

    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;

  }

  public function makeEventConfigData($data, $summedQuantities)
  {

    $event_config = $this->getEventConfig($data['id']);
    $event = $event_config->event;

    if (!$event_config) {
      return false;
    }

    if (!$event->status) {
      return false;
    }

    $remainder = $event_config->visitors_quantity_limitation - $event_config->visitors_quantity;

    if ($summedQuantities > $remainder) {

      return false;
    }

    $data['museum_id'] = $event_config ? $event->museum->id : false;
    // $total_price = $event_config->price * $data['quantity'];
    if(isset($data['sub_type'])){
      $total_price =  $data['sub_type'] == 'discount' ? $event->discount_price * $data['quantity'] :
                      ($data['sub_type'] == 'standart' ? $event->price * $data['quantity'] :
                        ($data['sub_type'] == 'free' ? 0 * $data['quantity'] :
                          ($data['sub_type'] == 'guide_price_am' ? $event->guide_price_am * $data['quantity'] :
                            ($data['sub_type'] == 'guide_price_other' ? $event->guide_price_other * $data['quantity'] : $event->price * $data['quantity']))));

    }
    else{
      $total_price = $event->price * $data['quantity'];
    }


    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];


    return $data;
  }

  public function makeEventData($data)
  {

    $event = $this->getEvent($data['id']);

    if (!$event) {
      return false;
    }

    $data['museum_id'] = $event->museum->id;

    if (isset($data['sub_type'])) {
      $total_price = $data['sub_type'] == 'discount' ? $event->discount_price * $data['quantity'] :
        ($data['sub_type'] == 'standart' ? $event->price * $data['quantity'] :
          ($data['sub_type'] == 'free' ? 0 * $data['quantity'] :
            ($data['sub_type'] == 'guide_price_am' ? $event->guide_price_am * $data['quantity'] :
              ($data['sub_type'] == 'guide_price_other' ? $event->guide_price_other * $data['quantity'] : $event->price * $data['quantity']))));

    }
    else{
      $total_price = $event->price * $data['quantity'];
    }


    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;
  }


  public function makeUnitedTicketData($data)
  {

    $min_museum_quantity = unitedTicketSettings()->min_museum_quantity;
    if ($min_museum_quantity > count($data['museum_ids'])) {
      return false;
    }
    $museums = Museum::whereIn('id', $data['museum_ids'])->get();
    $total_price = 0;
    $coefficient = ticketType($data['type'])->coefficient;

    foreach ($museums as $key => $museum) {
      $price = $museum->standart_tickets ? $museum->standart_tickets->price : 0;
      $discont_price = round($price - ($price * $coefficient));
      $total_price += $discont_price;
      $single_museum_total_price = $data['quantity'] * $discont_price;
      $data['united'][$key] = [
        'museum_id' => $museum->id,
        'price' => $discont_price,
        'quantity' => $data['quantity'],
        'total_price' => $single_museum_total_price
      ];
    }

    $data['total_price'] = $total_price * $data['quantity'];

    return $data;
  }

  //////////////////////////
  public function makeOtherServiceData($data)
  {
    $ticket = $this->getOtherServiceTicket($data['id']);

    if (!$ticket) {
      return false;
    }

    $data['museum_id'] = $ticket ? $ticket->museum->id : false;

    $total_price = $ticket->price * $data['quantity'];

    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];

    return $data;
  }

  public function makePartnerTicketData($data)
  {
    $partner_ticket = $this->getPartnerTicket($data['id']);
    $educational_program_price = null;


    if (!$partner_ticket) {
      return false;
    }

    if($data['sub_type'] == 'educational'){
      $educational_program = $this->getEducationalProgram($data['educational_id']);

      if (!$educational_program) {
        return false;
      }

      $educational_program_price = $educational_program->price;
    }

    $data['museum_id'] = $partner_ticket ? $partner_ticket->museum_id : false;
    $data['partner_id'] = $data['id'];

    $price = $data['sub_type'] == 'educational' ? $educational_program_price : $partner_ticket->price;

    $coefficient = $data['sub_type'] == 'educational' ? 1 : ticketType($data['sub_type'])->coefficient;
    $total_price = $price * $coefficient * $data['quantity'];


    $data['total_price'] = $total_price;
    $data['item_relation_id'] = $data['id'];



    return $data;
  }


  // ================ get function start ==============================

  public function getProduct($id, $quantity)
  {
    $product = Product::where(['id' => $id, 'status' => 1]);

    if($this->typeAtTimeOfSale == 'offline'){
     return $product->first();
    }

    return $product->where('quantity', '>', $quantity)->first();
  }

  public function getEvent($id)
  {
    return Event::where(['id' => $id, 'status' => 1])->first();
  }

  public function getEventConfig($id)
  {

    $event_config = EventConfig::where(['id' => $id, 'status' => 1])->whereColumn('visitors_quantity_limitation', '>', 'visitors_quantity')->first();
    if($event_config){
        $event = $this->getEvent($event_config->event_id);

        if($event){
          return $event_config;
        }

        return false;
    }

    return false;

  }

  public function getAvailableEventViaEventConfig($id){
    $event_config = EventConfig::where('id', $id)->first();
    if($event_config){

      return $event_config = EventConfig::where('id', $id)->first()->event->getCurrentTranslation->name;

    }
    else{
      return false;
    }

  }

  public function getStandartTicket($id)
  {
    return Ticket::where(['id' => $id, 'status' => 1])->first();
  }

  public function getSubscriptionTicket($id)
  {
    return TicketSubscriptionSetting::where(['id' => $id, 'status' => 1])->first();
  }
  public function addItemInPurchasedItem($data)
  {
    return PurchasedItem::create($data);
  }

  public function getGuide($id)
  {
    return GuideService::where('id', $id)->first();
  }

  public function getEducationalProgram($id)
  {
    return EducationalProgram::where(['id' => $id, 'status' => 1])->first();
  }

  public function getCorporative($id)
  {
    return CorporativeSale::where('id', $id)->first();
  }

  public function getSchoolTicket($id)
  {
    return TicketSchoolSetting::where('id', $id)->first();
  }

  public function getOtherServiceTicket($id)
  {
    return OtherService::where(['id' => $id, 'status' => 1])->first();
  }

  public function getPartnerTicket($id)
  {

      $partner = Partner::find($id);
      return $partner != null ? Ticket::where(['museum_id' =>$partner->museum_id, 'status' => 1])->first() : false;
  }
  public function createUnitedTickets($data)
  {

    $united = $data['united'];
    unset($data['museum_ids']);
    unset($data['united']);

    $purchased_item = PurchasedItem::create($data);

    foreach ($united as $key => $value) {
      $value['purchased_item_id'] = $purchased_item->id;
      PurchaseUnitedTickets::create($value);
    }

    return $purchased_item;
  }

  public function getAllMuseumsTikets($data)
  {
    $data = array_filter($data, function ($value) {
      return $value['type'] == 'united';
    });


    if (count($data) > 0) {

      if(!unitedTicketSettings()){
        return false;
      }
      $min_museum_quantity = unitedTicketSettings()->min_museum_quantity;

      $u_museum_ids = Ticket::pluck('museum_id')->toArray();

      foreach ($data as $u => $u_array) {
        if ($min_museum_quantity > count($u_array['museum_ids'])) {
          return false;
        }
        $difference = array_diff($u_array['museum_ids'], $u_museum_ids);
        if (!empty($difference)) {
          return false;
        }

      }
    }

    return true;

  }
}
