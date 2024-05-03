<?php
namespace App\Services\Corporative;

use App\Mail\CorporativeCouponEmail;
use App\Models\CorporativeSale;
use App\Models\Museum;
use App\Models\Purchase;
use App\Models\PurchasedItem;
use App\Services\FileUploadService;
use App\Services\Log\LogService;
use App\Traits\Purchase\PurchaseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Mail;
use Storage;

class CorporativeSaleService
{
    use PurchaseTrait;

    public function createCorporative($data)
    {
        
        if (array_key_exists('file', $data)) {
            $fileOrginalName = $data['file']->getClientOriginalName();
            $path = FileUploadService::upload($data['file'], 'files');
 
            if(!$path){
                session(['errorMessage' => 'Ինչ որ բան այն չէ ֆայլի հետ կապված']);
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

                $dataPurchase['purchase_type'] = 'offline';
                $dataPurchase['status'] = 1;
                $dataPurchase['items'][] = [
                    "type" => 'corporative',
                    "id" => $corporative->id,
                    "quantity" => 1
                ];
                $this->purchase($dataPurchase);

                unset($mailData);

            } else {
                session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
                DB::rollBack();
                return false;
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

    public function getElementById($id)
    {
        return CorporativeSale::find($id);
    }

    public function getEditItem($id)
    {
        $items = $this->getElementById($id);

        if($items->created_at->addHour() >= Carbon::now()){
            return $items;
        }

        return false;
    }

    public function updateCorporative($data, $id)
    {
        $corporative = CorporativeSale::find($id);

        if (array_key_exists('file', $data)) {
            $fileOrginalName = $data['file']->getClientOriginalName();
            $path = FileUploadService::upload($data['file'], 'files');
 
            if(!$path){
                session(['errorMessage' => 'Ինչ որ բան այն չէ ֆայլի հետ կապված']);
                return false;
            }  

            if($corporative->file_path){
                Storage::delete($corporative->file_path);
            };

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

            if((int) $data['price'] != (int) $corporative->price){
                $purchaseItem = PurchasedItem::where(['type' => 'corporative', 'item_relation_id' => $corporative->id])->first();
                $purchaseId = $purchaseItem->purchase_id;
                $purchaseItem->update(['total_price' => (int) $data['price']]);
                Purchase::where('id', $purchaseId)->update(['amount' => (int) $data['price']]);
            }

            $corporativeUpdate = $corporative->update($data);

            if($corporativeUpdate){
                $mailData = [
                    'coupon' => $coupon,
                    'museum_name' => $museumName,
                    'ttl_at' => Carbon::parse($newDate)->format('Y-m-d'),
                    'tickets_count' => $data['tickets_count'],
                ];

                Mail::send(new CorporativeCouponEmail($data['email'], $mailData));

                unset($mailData);

            } else {
                session(['errorMessage' => 'Ինչ որ բան այն չէ, խնդրում ենք փորձել մի փոքր ուշ']);
                DB::rollBack();
                return false;
            }

            unset($data['coupon']);

            if (array_key_exists('file_path', $data)) {
                unset($data['file_path']);
                $data['file'] = $fileOrginalName;
            }

            LogService::store($data, auth()->id(), 'corporative_sales', 'update');
            session(['success' => 'Կորպորատիվը հաջողությամբ թարմացվեց']);

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

    public function isMuseumCorporative($corporative)
    {
        $museumId = getAuthMuseumId();

        if($corporative->museum_id == $museumId){
            return true;
        } else {
            return false;
        }

    }

    public function deleteFile($id)
    {
        $corporative = $this->getElementById($id);

        if($corporative){
            $havePermission = $this->isMuseumCorporative($corporative);
            if($havePermission) {
                $this->isMuseumCorporative($corporative);
                $path = $corporative->file_path;
        
                $corporative->update(['file_path' => null]);
        
                Storage::delete($path);

                return true;
            }
        }

        return false;

    }
    
}
