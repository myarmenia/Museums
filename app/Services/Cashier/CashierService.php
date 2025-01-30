<?php
namespace App\Services\Cashier;

use App\Http\Resources\EducationalPrograms\EducationalProgramsResource;

use App\Models\CorporativeSale;
use App\Models\CorporativeVisitorCount;
use App\Models\EducationalProgram;
use App\Models\Event;
use App\Models\Museum;
use App\Models\OtherService;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketPdf;
use App\Traits\Purchase\PurchaseTrait;
use Carbon\Carbon;

class CashierService
{
    use PurchaseTrait;
    // protected $chatRepository;
    // public function __construct(ChatRepository $chatRepository)
    // {
    //     $this->chatRepository = $chatRepository;
    // }


    public function getAllData()
    {
        $data = [];
        $museumId = museumAccessId();
        $museum = Museum::with(['guide',
        'events' => function ($query){
            $query->orderBy('id', 'DESC')
                  ->where('status', 1)
                  ->where('end_date','>',now()->format('Y-m-d'))
                  ->get();
        },
        'aboniment' => function ($query){
            $query->where('status', 1)->first();
        },
        'educational_programs' => function ($query) {
            $query->orderBy('id', 'DESC')->where('status', 1)->get();
        },
        'other_services' => function ($query){
            $query->orderBy('id', 'DESC')->where('status', 1)->get();
        },
        'partners' => function ($query){
          $query->orderBy('id', 'DESC')->where('status', 1)->get();
      },
        ])->find($museumId);

        if(!$ticketPrice = Ticket::where('museum_id', $museumId)->first()){
            session(['errorMessage' => 'Նախ ստեղծեք տոմս']);
            return ['success' => false, 'error' => "dontTicket"];
        };

        $ticketPrice = $ticketPrice->price;

        $ticketType = ticketType('discount');

        $data['ticket'] = [
            'price' => $ticketPrice,
            'sale' =>  intval($ticketPrice * $ticketType->coefficient),
        ];

        if($museum->guide){
            $data['ticket']['guid-arm'] = $museum->guide->price_am;
            $data['ticket']['guid-other'] = $museum->guide->price_other;
        }

        $data['educational'] = $this->customEducationalResource($museum->educational_programs);
        if($museum->aboniment){
            $data['aboniment'] = [
                'price' => $museum->aboniment->price
            ];
        }

        if($museum->events->count()){
            $data['events'] = $museum->events;

        }
        if($museum->other_services->count()){
          $data['other_services'] = $museum->other_services;
        }
        if($museum->partners->count()){
          $data['partners'] = $museum->partners;
        }

        return ['success' => true, 'data' => $data];
    }

    public function customEducationalResource($data)
    {
        $readyData = [];

        if(!$data) {
            return [];
        }

        foreach ($data as $key => $item) {

            $readyData [] = [
                "id" => $item->id,
                "name" => $item->translation('am')->name,
                "description" => $item->translation('am')->description,
                "price" => $item->price,
                "min_quantity" => $item->min_quantity,
                "max_quantity" => $item->max_quantity,
            ];
        }

        return $readyData;


    }

    public function checkCoupon($data)
    {
        $museumId = museumAccessId();

        $corporative = $this->getMuseumCorporative($museumId, $data['coupon']);
        if($corporative){
            return ['success' => true, 'data' => [
                'companyName' => $corporative->name,
                'availableTickets' => $corporative->tickets_count - $corporative->visitors_count
            ]];
        }

        return ['success' => false, 'message' => "Տվյալ կուպոն ով տվյալ չի գտնվել"];

    }

    public function getMuseumCorporative($museumId, $coupon)
    {
        $dateNow = Carbon::now()->format('Y-m-d');

        return CorporativeSale::where('museum_id', $museumId)->whereDate('ttl_at', '>=', $dateNow)->where('coupon', $coupon)->first();
    }

    public function getEventDetails($eventId)
    {
        if($museumId = museumAccessId()) {

            $event = Event::with('event_configs')->where("museum_id", $museumId)->find($eventId);

            return $event;
        }

        session(['errorMessage' => 'Ինչ որ բան այն չէ']);

        return false;
    }

    public function getMuseumProduct($data)
    {
        $museumId = museumAccessId();

        return Product::with('images')->where(['museum_id'=>$museumId, 'status' => 1])->where('quantity', '>', 0)->filter($data)->orderBy('id', 'DESC')->paginate(10)->withQueryString();
    }

    public function showLastTicket()
    {
        $museumId = museumAccessId();

        $data = TicketPdf::where('museum_id', $museumId)->orderBy('id', 'DESC')->first();

        return $data;
    }

    public function getOtherServiceDetails($otherServiceId)
    {
        if($museumId = museumAccessId()) {
            $otherService = OtherService::with(['item_translations' => function($query) {
              $query->where('lang', 'am');
          }])->where('museum_id', $museumId)->find($otherServiceId);
            return $otherService;
        }

        session(['errorMessage' => 'Ինչ որ բան այն չէ']);

        return false;
    }
    public function getPartnerDetails($partnerId)
    {
        if($museumId = museumAccessId()) {
            $partner = Partner::with('museum.standart_tickets','museum.guide')->where('museum_id', $museumId)->find($partnerId);

            $partner['partner_educational_program'] = EducationalProgram::where('museum_id', $museumId)
                    ->with('translationAm') // Eager load the Armenian translation
                    ->get();


            return $partner;
        }

        session(['errorMessage' => 'Ինչ որ բան այն չէ']);

        return false;
    }


}
