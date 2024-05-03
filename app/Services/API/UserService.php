<?php
namespace App\Services\API;

use App\Models\Country;
use App\Services\FileUploadService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    
    public function edit($data)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (array_key_exists('avatar', $data)) {
            $path = FileUploadService::upload($data['avatar'], 'user/'.$user->id);
            $data['avatar'] = $path;
        }

        if (array_key_exists('country', $data)) {
           $countryId = Country::where('key', $data['country'])->first()->id;
           if($countryId){
               $data['country_id'] = $countryId;
               unset($data['country']);
           }
        }

        $user->update($data);

        $user['card_count'] = $user->carts()->get()->count(); 
        $user['country_key'] = $user->country ? $user->country->key : null;
        
        $user->avatar = $user->avatar? route('get-file', ['path' => $user->avatar]) : "";
        unset($user['country']);
        return $user;
    }

    public function editPassword($data)
    {
        $hashedPassword = auth('api')->user()->password;

        if(Hash::check($data['currentPassword'], $hashedPassword)){
            auth('api')->user()->update([
                'password' => Hash::make($data['password'])
            ]);
            return true;
        }
        return false;

    }
}
