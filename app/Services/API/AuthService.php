<?php
namespace App\Services\API;

use App\Models\API\VerifyUser;
use App\Models\Country;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Arr;
use App\Mail\SendVerifyToken;
use Mail;
use Illuminate\Support\Str;

class AuthService
{
    public function signup($data)
    {
        $data['password'] = bcrypt($data['password']);
        $data['status'] = 0;

        if(array_key_exists('country', $data) &&  $data['country']){
            $data['country_id'] = Country::where('key', $data['country'])->first()->id;
        }

        $user = User::create($data);

        $user->assignRole('visitor');

        if ($user) {
            $token = mt_rand(10000, 99999);
            $email = $user->email;
            $verify = VerifyUser::create([
                'email' => $email,
                'verify_token' => $token
            ]);

            Mail::send(new SendVerifyToken($email, $token));

            return true;
        }

        return false;

        // $credentials = Arr::only($data, ['email', 'password']);

        // if (!$token = JWTAuth::attempt($credentials)) {
        //     return response()->json(['error' => translateMessageApi('user-not-found')], 401);
        // }

        // return [
        //     'authUser' => $user->toArray(),
        //     'token' => $token
        // ];
    }

    public function signupGoogle($data)
    {
        dd("sharunakel aystexic"); 
        $data['password'] = bcrypt($data['password']);
        $data['status'] = 1;

        if(array_key_exists('country', $data) &&  $data['country']){
            $data['country_id'] = Country::where('key', $data['country'])->first()->id;
        }

        $user = User::create($data);

        $user->assignRole('visitor');

        if ($user) {
            $token = mt_rand(10000, 99999);
            $email = $user->email;
            $verify = VerifyUser::create([
                'email' => $email,
                'verify_token' => $token
            ]);

            Mail::send(new SendVerifyToken($email, $token));

            return true;
        }

        return false;

        // $credentials = Arr::only($data, ['email', 'password']);

        // if (!$token = JWTAuth::attempt($credentials)) {
        //     return response()->json(['error' => translateMessageApi('user-not-found')], 401);
        // }

        // return [
        //     'authUser' => $user->toArray(),
        //     'token' => $token
        // ];
    }

    public function login($request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $getUserVerificate = VerifyUser::where('email', $credentials['email'])->first();
  
            if($getUserVerificate){
                try {
                   $this->resendVerify($credentials);

                   return [
                        'success' => false, 
                        'message' => translateMessageApi('email_verified'),
                        'is_verify' => false,
                        'status' => 401
                    ];
                } catch (\Throwable $th) {
                    throw new \Exception(translateMessageApi('something-went-wrong'), 500);
                }
 
                // throw new \Exception(translateMessageApi('email_verified'), 401);
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                return [
                    'success' => false, 
                    'message' => translateMessageApi('user-email-or-password-not-found'),
                    'status' => 401
                ];
                // throw new \Exception(translateMessageApi('user-email-or-password-not-found'), 401);
            }

            if(!auth()->user()->roles()->get()->where('name', 'visitor')->count()) {
                return [
                    'success' => false, 
                    'message' => translateMessageApi('wrong-role-for-login'),
                    'status' => 401
                ];
                // throw new \Exception(translateMessageApi('wrong-role-for-login'), 401);
            }

            if (auth()->user()->status === 0) {
                return [
                    'success' => false, 
                    'message' => translateMessageApi('user-blocked'),
                    'status' => 401
                ];
                // throw new \Exception(translateMessageApi('user-blocked'), 401);
            }

            auth()->user()->update([
                'ip' => request()->ip(),
                'login_at' => now(),
            ]);
            
            $authUser = auth()->user();
            $authUser['card_count'] = $authUser->carts()->get()->count(); 
            $authUser = $authUser->toArray();

            return [
                'success' => true,
                'authUser' => $authUser,
                'access_token' => $token,
                'status' => 200
            ];
        } catch (\Exception $e) {
            throw $e;
        }


    }

    public function checkVerifyToken($data)
    {
      $haveOrNot = VerifyUser::where('email', $data['email'])->where('verify_token', $data['token'])->first();
  
      if($haveOrNot){
        $user = User::where('email', $data['email'])->first();
        $token = JWTAuth::fromUser($user);
        $statusapproved = $user->update([
          'status' => 1
        ]);

        if($statusapproved){
            VerifyUser::where('email', $data['email'])->delete();
            return [
                'success' => true, 
                'message' => translateMessageApi('status-active'),
                'access_token' => $token,
                'authUser' => $user
            ];
        }

        return false;
      }
  
      return false;
    }

    public function resendVerify($data)
    {
        $email = $data['email'];

        if($verify = VerifyUser::where('email', $email)->first()){
            $token = mt_rand(10000, 99999);
            $verify->update([
                'verify_token' => $token
            ]);

            Mail::send(new SendVerifyToken($email, $token));

            return true;
        }

        return false;
        
    }

    public function signupInfo($data)
    {
        if(array_key_exists('country', $data) &&  $data['country']){
            $data['country_id'] = Country::where('key', $data['country'])->first()->id;
        }

        if (array_key_exists('password', $data)) {
            unset($data['password']);
        }

        $user = auth('api')->user()->update($data);

        return $user;
    }

}
