<?php
namespace App\Services\Phone;
use App\Models\PhoneNumber;
use App\Services\FileUploadService;


class PhoneService
{
    public static function createPhone($data)
    {
        $phones = $data['phones'];
        $museumId = $data['museum_id'];
        $readyPhone = [];
        
        foreach ($phones as $idx => $phone) {
            if($phone){
                $readyPhone[] = [
                    'museum_id' => $museumId,
                    'phone_name' => $idx,
                    'number' => $phone,
                ];  
            }
            
        }

        PhoneNumber::insert($readyPhone);
        return;

    }

    public static function updatePhone($data, $museumId)
    {
        $phones = $data['phones'];
        $readyPhone = [];

        PhoneNumber::where('museum_id', $museumId)->delete();

        foreach ($phones as $idx => $phone) {
            if($phone){
                $readyPhone[] = [
                    'museum_id' => $museumId,
                    'phone_name' => $idx,
                    'number' => $phone,
                ];  
            }
            
        }

        PhoneNumber::insert($readyPhone);
        
        return;

    }

    

}
