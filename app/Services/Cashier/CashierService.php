<?php
namespace App\Services\Cashier;
use App\Models\Museum;
use App\Models\Ticket;

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

   
}
