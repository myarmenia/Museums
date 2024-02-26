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
                throw new \Exception(translateMessageApi('email_verified'), 401);
            }

            if (!$token = JWTAuth::attempt($credentials)) {
                throw new \Exception(translateMessageApi('user-email-or-password-not-found'), 401);
            }

            if(!auth()->user()->roles()->get()->where('name', 'visitor')->count()) {
                throw new \Exception(translateMessageApi('wrong-role-for-login'), 401);
            }

            if (auth()->user()->status === 0) {
                throw new \Exception(translateMessageApi('user-blocked'), 401);
            }

            auth()->user()->update([
                'ip' => request()->ip(),
                'login_at' => now(),
            ]);
            
            $authUser = auth()->user()->toArray();

            return [
                'authUser' => $authUser,
                'token' => $token
            ];
        } catch (\Exception $e) {
            throw $e;
        }


    }

    public function checkVerifyToken($data)
    {
      $haveOrNot = VerifyUser::where('email', $data['email'])->where('verify_token', $data['token'])->first();
  
      if($haveOrNot){
        $statusapproved = User::where('email', $data['email'])->update([
          'status' => 1
        ]);

        if($statusapproved){
             VerifyUser::where('email', $data['email'])->delete();
            return true;
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

}
