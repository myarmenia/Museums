<?php

namespace App\Http\Controllers\cashier;

use App\Http\Controllers\Controller;
use App\Models\EducationalProgram;
use App\Models\GuideService;
use App\Traits\NodeApi\QrTokenTrait;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends CashierController
{

  use PurchaseTrait;
  use QrTokenTrait;
  public function __invoke(Request $request)
  {

    try {

        DB::beginTransaction();
        $requestDatForValidation = $request->except('partner_id','comment');
        session(['open_tab' =>'navs-top-partners']);
        $data['purchase_type'] = 'offline';
        $data['status'] = 1;
        $data['items'] = [];
        if(!is_null($request->comment)){
            $data['comment']['comment'] = $request->comment;
            $data['comment']['type'] = 'partner';
        }else{

        }

        $museumId = getAuthMuseumId();


          $filteredData = array_filter($requestDatForValidation, function ($value) {

            return !is_null($value);
          });
          // dd($filteredData);

          if (empty($filteredData)) {
              session([
                'errorMessage' => 'Պետք է պարտադիր նշված լինի  տոմսի քանակ դաշտերը։',

              ]);
              return redirect()->back();
          }


          if(isset($filteredData['educational']["quantity"]) && !isset($filteredData["educational"]['educational_id'])){
              session([
                'errorMessage' => 'Պետք է պարտադիր ընտրել գործընկերոջ կրթական ծրագիրը',

              ]);
              return redirect()->back();
            }


          if(!isset($filteredData['educational']["quantity"]) && isset($filteredData["educational"]['educational_id'])){
            session([
              'errorMessage' => 'Պետք է պարտադիր ընտրել գործընկերոջ կրթական ծրագրի քանակ դաշտը',

            ]);


            return redirect()->back();
          }
          if(isset($filteredData['educational']["quantity"]) && isset($filteredData["educational"]['educational_id'])){
            // dd($filteredData['partner_education_program_quantity'],$filteredData['partner_education_program']);
            $educational_program = EducationalProgram::where('id',$filteredData["educational"]['educational_id'])->first();
              if($filteredData['educational']["quantity"]<$educational_program->min_quantity || $filteredData['educational']["quantity"]>$educational_program->max_quantity){
                session([
                  'errorMessage' => 'Պետք է գոծընկերոջ կրթական ծրագրի քանակ դաշտը ընկած լինի '.$educational_program->min_quantity .'- '.$educational_program->max_quantity ." միջակայքում",

                ]);
                return redirect()->back();

              }

          }








        $guid = GuideService::where('museum_id', $museumId)->first();
        $haveValue = false;

          // dd($requestDatForValidation);
        foreach ($requestDatForValidation as $key => $item) {
          // dump($item);
            if ($item ) {
                $haveValue = true;
                if( $key!="educational"){
                  $newItem = [
                      "type" => "partner",
                      "quantity" => (int) $item,
                      "id"=>$request->partner_id,// գործընկերոջ id
                      "sub_type"=>$key //տոմսի տեսակը
                  ];
                }

                if (($key === 'guide_other' || $key === 'guide_am') && $guid) {

                    $newItem["id"] = $guid->id;
                    $newItem["partner_id"] = $request->partner_id;
                    if($key === 'guide_other'){
                      $newItem["sub_type"] = "partner_guide_other";
                      $newItem["type"]="guide_other";

                    }else{
                       $newItem["sub_type"]="partner_guide_am";
                       $newItem["type"]="guide_am";
                    }
                }

                if ($key === 'educational') {
                  // dd($item);
                  $newItem = [
                    "type" => "partner",
                    "quantity" => (int) $item['quantity'],
                    "id"=>$request->partner_id,// գործընկերոջ id
                    "sub_type"=>"educational", //տոմսի տեսակը
                    "educational_id"=>$item['educational_id']

                ];
              }


                $data['items'][] = $newItem;

            }
        }


        if(!$haveValue){
          session(['errorMessage' => 'Լրացրեք քանակ դաշտը']);

          DB::rollBack();
          return redirect()->back();
        }

        $addPurchase = $this->purchase($data);

            if ( $addPurchase) {

                $addQr = $this->getTokenQr( $addPurchase->id);

                if ($addQr) {
                    $pdfPath = $this->showReadyPdf( $addPurchase->id);

                    session(['success' => 'Տոմսերը ավելացված է']);

                    DB::commit();
                    return redirect()->back()->with('pdfFile', $pdfPath);
                }
            }



        DB::rollBack();

        return redirect()->back();


    } catch (\Exception $e) {
        session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        DB::rollBack();
        dd($e->getMessage());
        return redirect()->back();
    } catch (\Error $e) {
        session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
        DB::rollBack();
        dd($e->getMessage());
        return redirect()->back();
    }
  }
}
