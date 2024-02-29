<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Auth\ResendVerifyRequest;
use App\Http\Requests\SingupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\API\AuthService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\FirebaseToken;
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
class AuthController extends BaseController
{
    public $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        // $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    public function login(Request $request)
    {
        try {
            $data = $this->authService->login($request);

            $readyData = [
                'authUser' => $data['authUser'],
                'access_token' => $data['token'],
            ];

            return response()->json($readyData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    public function me()
    {
        if($me = auth('api')->user()){
            return response()->json($me);
        }

        return response()->json(['error' => translateMessageApi('user-not-found')], 401);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['success' => true]);
    }

    public function signup(SingupRequest $request)
    {
        $data = $this->authService->signup($request->all());

        if($data){
           return response()->json(['success' => true, 'message' => translateMessageApi('email_verified')]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')]);
        // $readyData = [
        //     'authUser' => $data['authUser'],
        //     'access_token' => $data['token'],
        // ];

        // return response()->json($readyData);
    }

    public function signupGoogle(Request $request)
    {
       $token3 = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjZmOTc3N2E2ODU5MDc3OThlZjc5NDA2MmMwMGI2NWQ2NmMyNDBiMWIiLCJ0eXAiOiJKV1QifQ.
       eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjExMTg5OTk2MTY4OTk5Njc2OTI2MCIsImVtYWlsIjoiYmFnaGRhc2FyeWFuLmdldm9yZy45N0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmJmIjoxNzA5MDM0MDY3LCJuYW1lIjoiR2V2b3JnIEJhZ2hkYXNhcnlhbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NJdUJrczBzbDEwQldnaVdIcTd0QmE0MFVONkRxRFZKeVdCeG91SXl2cE1QQT1zOTYtYyIsImdpdmVuX25hbWUiOiJHZXZvcmciLCJmYW1pbHlfbmFtZSI6IkJhZ2hkYXNhcnlhbiIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA5MDM0MzY3LCJleHAiOjE3MDkwMzc5NjcsImp0aSI6ImI2M2QyNTIyOWVjMDlhMDgwZWMyMDI5YzlkNTk1MzFlMjk5MjY3YWUifQ.QQbEbxNyFrwAJzxf1p7FUGVnFeHrBV6bwZZBHL92vk9_tFNTRigZOSX5LrNilrEqez2Qs7jxc6q8IlCrugijqj3Kp3oaVHvLSPC-QAlNnoeFqsLFpdnw5rSlM80ThLEkDcU_rMl7BJWopHqzvJw_0JMiqR4UxzgU3MkAqF7lnRVjiqm-BQYaZ-BWjjD4GsHgC6A8e9EPJQh36ZAXzrNPIoldxd1ctCcpH42hKjDpTNymDxDLp_Ogazzu617v_Qq0QOGpQ6U0GkGZJ9_f-7t4d4lE-yFrz6L-CmZduJY7zE-OU7MdFlb3LQ7WIfo-BBeyN1Ec7ixNWkDVPZNOyxezjQ';
       $token4='eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjExMTg5OTk2MTY4OTk5Njc2OTI2MCIsImVtYWlsIjoiYmFnaGRhc2FyeWFuLmdldm9yZy45N0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmJmIjoxNzA5MDM0MDY3LCJuYW1lIjoiR2V2b3JnIEJhZ2hkYXNhcnlhbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NJdUJrczBzbDEwQldnaVdIcTd0QmE0MFVONkRxRFZKeVdCeG91SXl2cE1QQT1zOTYtYyIsImdpdmVuX25hbWUiOiJHZXZvcmciLCJmYW1pbHlfbmFtZSI6IkJhZ2hkYXNhcnlhbiIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA5MDM0MzY3LCJleHAiOjE3MDkwMzc5NjcsImp0aSI6ImI2M2QyNTIyOWVjMDlhMDgwZWMyMDI5YzlkNTk1MzFlMjk5MjY3YWUifQ';
       $secret = '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';
      $id = '1690223887-9gsqs2ia6ua71c2fkup3vvp3l267clgh.apps.googleusercontent.com';
      
      $token = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjU1YzE4OGE4MzU0NmZjMTg4ZTUxNTc2YmE3MjgzNmUwNjAwZThiNzMiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjExMTg5OTk2MTY4OTk5Njc2OTI2MCIsImVtYWlsIjoiYmFnaGRhc2FyeWFuLmdldm9yZy45N0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmJmIjoxNzA4OTI3NDI1LCJuYW1lIjoiR2V2b3JnIEJhZ2hkYXNhcnlhbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NJdUJrczBzbDEwQldnaVdIcTd0QmE0MFVONkRxRFZKeVdCeG91SXl2cE1QQT1zOTYtYyIsImdpdmVuX25hbWUiOiJHZXZvcmciLCJmYW1pbHlfbmFtZSI6IkJhZ2hkYXNhcnlhbiIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA4OTI3NzI1LCJleHAiOjE3MDg5MzEzMjUsImp0aSI6IjM2ZWViMDIxZWVhNDBjNDc1ODQxYjgzMmU4OWQ2NGJhMzNiY2M0MzgifQ.kND1phmAubqh__YoEjcXoBw-dupAWLlWkMOQzM7EyTHTcroHmZ1nSw_vr0MH84XbE4IjR1krkTo-7g8L8MvZW8L57CeXhWJsrkJpM5u-CRylspKd_A-Rs2w3Kvrd2dRZMUtJMZCEeLgtO5FEaOqKp6ciST8HKVLzkpn_8YyOAd8aznnJlmvoHGXBeiOvjS6kuBFWpgawYJZF61bq2fpJ9mslCw-4A6LGEJP1E8PnSFoZ6QCOShaDTywn-teTzmSZ24INm0E3O6lWaiptGjUQS13ZZdO4_Qxez7FdfK3sJL4it5G9PW_sPSNO3-2n7Z83-z5vBQxoFUTw98Q9zSdZ4g';
      $tokenx = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjZmOTc3N2E2ODU5MDc3OThlZjc5NDA2MmMwMGI2NWQ2NmMyNDBiMWIiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjEwNzMyNzg1NDMzODA2MzYzNjE5OCIsImVtYWlsIjoidHJhZGluZ3RyYWRlckBtYWlsLnJ1IiwiZW1haWxfdmVyaWZpZWQiOnRydWUsIm5iZiI6MTcwOTEwNDY4OCwibmFtZSI6IkFybSBUcmFkZXJzIiwicGljdHVyZSI6Imh0dHBzOi8vbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbS9hL0FDZzhvY0xac3RNczJtWV9ZYi1QUkxLMVBYa29femZRb19HQkdPazk5aUFhMXZHcWtnPXM5Ni1jIiwiZ2l2ZW5fbmFtZSI6IkFybSIsImZhbWlseV9uYW1lIjoiVHJhZGVycyIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA5MTA0OTg4LCJleHAiOjE3MDkxMDg1ODgsImp0aSI6IjYyNjBiMTU4Y2M4NGVjYjY3ZTEyNjYwM2IxYWFmOTk2YzE5ZmNjYmUifQ.eiqJqu3ajmn4Y51ASkRBlYPyfx81H9EISTToLNlSeKqFqiE7ZrvbPTinU7ZwD5eKQ1gSUs1y8hXOfJi0jNqLqpOj3lNiEB9HFb1mxzHy70BMa2VNCid_BMaZGL-QLgJg-CSuwZa38ELpI7yW6D-s4V6EzvdDp0LU0U5hH9ncSGbpmlY-QzbeWwo65sd8zrNvKj1srri3fIAlalxN5jaI0nT1ja3kH8SUMaSLuv-rrFfgU32H4SpSBLSor5fx1qPqEzMLXF5lQxe9P10X9c2Hrm4DIcm4kTokyeyyCZKfkoPxFPXLMPAglZZ-wRSYd4BErw32-WvoYPAQyn0As0-dQg';
      dd(Socialite::driver('google')->userFromToken($tokenx));
       dd(JWT::decode($token, new Key($secret, 'RS256')));
       Socialite::driver('google')->redirect();
       dd(Socialite::driver('google')->userFromToken($token));
       
       $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $token4);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($curl_handle));
        curl_close($curl_handle);
        if (!isset($response->email)) {
            return response()->json(['error' => 'wrong google token / this google token is already expired.'], 401);
        }
        dd($curl_handle);
        $key = '1690223887-9gsqs2ia6ua71c2fkup3vvp3l267clgh';
        $response = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo', [
            'access_token' => $token,
        ]);
        dd($response);
        $token1 = 'eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjExMTg5OTk2MTY4OTk5Njc2OTI2MCIsImVtYWlsIjoiYmFnaGRhc2FyeWFuLmdldm9yZy45N0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmJmIjoxNzA4OTI3NDI1LCJuYW1lIjoiR2V2b3JnIEJhZ2hkYXNhcnlhbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NJdUJrczBzbDEwQldnaVdIcTd0QmE0MFVONkRxRFZKeVdCeG91SXl2cE1QQT1zOTYtYyIsImdpdmVuX25hbWUiOiJHZXZvcmciLCJmYW1pbHlfbmFtZSI6IkJhZ2hkYXNhcnlhbiIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA4OTI3NzI1LCJleHAiOjE3MDg5MzEzMjUsImp0aSI6IjM2ZWViMDIxZWVhNDBjNDc1ODQxYjgzMmU4OWQ2NGJhMzNiY2M0MzgifQ';
        dd(Socialite::driver('google')->userFromToken($token));
        $response = Http::get('https://www.googleapis.com/oauth2/v3/tokeninfo', [
            'id_token' => $token,
        ]);
dd($response->json());
         $decoded = JWT::decode($token, new Key($key, 'RS256'));
        dd($decoded);
        $jwtToken = new Token($token1);
        $decoded = JWTAuth::decode($jwtToken);
        dd($decoded);
        // dd(JWTAuth::decode($token, env('JWT_SECRET'), array('HS256')));
        dd(FirebaseToken::decode($token));
        dd($request->all());
        $data = $this->authService->signupGoogle($request->all());
dd("finish");
        // $readyData = [
        //     'authUser' => $data['authUser'],
        //     'access_token' => $data['token'],
        // ];

        // return response()->json($readyData);
    }

    // public function refresh()
    // {
    //     return $this->respondWithToken(auth('api')->refresh());
    // }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    public function checkVerifyToken(Request $request)
    {
        $haveOrNot = $this->authService->checkVerifyToken($request->all());

        if($haveOrNot){
            return response()->json(['success' => true, 'message' => translateMessageApi('status-active')]);
        }
 
        return response()->json(['success' => false, 'message' => translateMessageApi('wrong-code')]);

    }

    public function resendVerify(ResendVerifyRequest $request)
    {
        $send = $this->authService->resendVerify($request->all());

        if($send){
            return response()->json(['success' => true, 'message' => translateMessageApi('email_verified')]);
        }

        return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong'), 500]);
        
    }
}
