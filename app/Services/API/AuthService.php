<?php
namespace App\Services\API;

use App\Models\API\VerifyUser;
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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'status' => 0,
            'password' => bcrypt($data['password']),
        ]);

        $user->assignRole('student');

        if ($user) {
            $token = sha1(Str::random(80));
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

            if (auth()->user()->status === 0) {
                throw new \Exception(translateMessageApi('user-blocked'), 401);
            }

            auth()->user()->update([
                'ip' => request()->ip(),
                'login_at' => now(),
            ]);
            
            $authUser = auth()->user()->toArray();
            $authUser['avatar'] = $authUser['avatar']?route('get-file', ['path' => $authUser['avatar']]): "";

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

}
