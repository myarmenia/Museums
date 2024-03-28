<?php
namespace App\Services\Cashier;
use App\Models\CorporativeSale;
use App\Models\CorporativeVisitorCount;
use App\Models\Event;
use App\Models\Museum;
use App\Models\Product;
use App\Models\Ticket;
use Carbon\Carbon;

class CashierService 
{
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
            $query->orderBy('id', 'DESC')->where('status', 1)->get();
        },
        'aboniment' => function ($query){
            $query->where('status', 1)->first();
        },
        'educational_programs' => function ($query) {
            $query->orderBy('id', 'DESC')->where('status', 1)->get();
        },])->find($museumId);

        $ticketPrice = Ticket::where('museum_id', $museumId)->first()->price;
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

        if($museum->events){
            $data['events'] = $museum->events;
        }

        if($museum->products){
            $data['products'] = $museum->products;
        }


        return $data;
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
       
        $corporative = $this->getCorporative($museumId, $data['coupon']);
        if($corporative){
            return ['success' => true, 'data' => [
                'companyName' => $corporative->name,
                'availableTickets' => $corporative->tickets_count - $corporative->visitors_count
            ]];
        }

        return ['success' => false, 'message' => "Տվյալ կուպոն ով տվյալ չի գտնվել"];

    }

    public function corporativeTicket($data)
    {
        $museumId = museumAccessId();
        $coupon = $data['corporative-ticket'];

        $corporative = $this->getCorporative($museumId, $coupon);
        if($corporative){
            $countBuyTicket = $data['buy-ticket'];
            CorporativeVisitorCount::create([
                'corporative_id' => $corporative->id,
                'count' => $countBuyTicket
            ]);

            $existVisitorCount = $corporative->visitors_count;

            $corporative->update([
                'visitors_count' => $existVisitorCount + $countBuyTicket
            ]);



            dd("avelacnel email uxarkumy ev vajarman logikan ");

            // return ['success' => true, 'data' => [
            //     'companyName' => $corporative->name,
            //     'availableTickets' => $corporative->tickets_count - $corporative->visitors_count
            // ]];
        }

        dd($data);
    }

    public function getCorporative($museumId, $coupon)
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

    public function getProduct($data)
    {
        $museumId = museumAccessId();

        return Product::where(['museum_id'=>$museumId, 'status'=>1])->filter($data)->orderBy('id', 'DESC')->paginate(10)->withQueryString();
    }

   
}
