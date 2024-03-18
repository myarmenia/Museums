<?php
namespace App\Services\Corporative;

use App\Mail\CorporativeCouponEmail;
use App\Models\CorporativeSale;
use App\Models\Museum;
use App\Services\FileUploadService;
use App\Services\Log\LogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;

class CorporativeSaleService
{

    public function createCorporative($data)
    {
        
        if (array_key_exists('file', $data)) {
            $fileOrginalName = $data['file']->getClientOriginalName();
            $path = FileUploadService::upload($data['file'], 'files');
 
            if(!$path){
                session(['errorMessage' => 'Ինչ որ բան այն չէ ֆալյի հետ կապված']);
                return false;
            }  

            $data['file_path'] = $path;

            unset($data['file']);

        }

        try {
            DB::beginTransaction();
            $date = Carbon::now();
            $newDate = $date->addYear();
            $coupon = $this->generateUniqueCoupon();
            $data['coupon'] = $coupon;
            $data['museum_id'] = getAuthMuseumId();
            $data['ttl_at'] = $newDate;
            $museumName = $this->getMuseumNameById($data['museum_id']);
            $data['museum_name'] = $museumName;
            
            $corporative = CorporativeSale::create($data);
            if($corporative){
                $mailData = [
                    'coupon' => $coupon,
                    'museum_name' => $museumName,
                    'ttl_at' => Carbon::parse($newDate)->format('Y-m-d'),
                    'tickets_count' => $data['tickets_count'],
                ];

                Mail::send(new CorporativeCouponEmail($data['email'], $mailData));

                unset($mailData);
            }

            unset($data['coupon']);

            if (array_key_exists('file_path', $data)) {
                unset($data['file_path']);
                $data['file'] = $fileOrginalName;
            }

            LogService::store($data, auth()->id(), 'corporative_sales', 'store');

            DB::commit();

            return true;
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

    public function generateUniqueCoupon()
    {
        $coupon = $this->generateCoupon();
        $corporative = CorporativeSale::pluck('coupon')->toArray();

        while (in_array($coupon, $corporative)) {
            $coupon = $this->generateCoupon();
        }
        
        return $coupon;
    }

    public function generateCoupon()
    {
        return Str::random(8);
    }

    public function getMuseumNameById($id)
    {
        $museum = Museum::with('translationsAdmin')->find($id);

        return $museum->translationsAdmin[0]->name;
    }
    
}
