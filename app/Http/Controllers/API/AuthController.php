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
        $token = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjU1YzE4OGE4MzU0NmZjMTg4ZTUxNTc2YmE3MjgzNmUwNjAwZThiNzMiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjExMTg5OTk2MTY4OTk5Njc2OTI2MCIsImVtYWlsIjoiYmFnaGRhc2FyeWFuLmdldm9yZy45N0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmJmIjoxNzA4OTI3NDI1LCJuYW1lIjoiR2V2b3JnIEJhZ2hkYXNhcnlhbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NJdUJrczBzbDEwQldnaVdIcTd0QmE0MFVONkRxRFZKeVdCeG91SXl2cE1QQT1zOTYtYyIsImdpdmVuX25hbWUiOiJHZXZvcmciLCJmYW1pbHlfbmFtZSI6IkJhZ2hkYXNhcnlhbiIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA4OTI3NzI1LCJleHAiOjE3MDg5MzEzMjUsImp0aSI6IjM2ZWViMDIxZWVhNDBjNDc1ODQxYjgzMmU4OWQ2NGJhMzNiY2M0MzgifQ.kND1phmAubqh__YoEjcXoBw-dupAWLlWkMOQzM7EyTHTcroHmZ1nSw_vr0MH84XbE4IjR1krkTo-7g8L8MvZW8L57CeXhWJsrkJpM5u-CRylspKd_A-Rs2w3Kvrd2dRZMUtJMZCEeLgtO5FEaOqKp6ciST8HKVLzkpn_8YyOAd8aznnJlmvoHGXBeiOvjS6kuBFWpgawYJZF61bq2fpJ9mslCw-4A6LGEJP1E8PnSFoZ6QCOShaDTywn-teTzmSZ24INm0E3O6lWaiptGjUQS13ZZdO4_Qxez7FdfK3sJL4it5G9PW_sPSNO3-2n7Z83-z5vBQxoFUTw98Q9zSdZ4g';
        // dd($token);
        $key = '1690223887-9gsqs2ia6ua71c2fkup3vvp3l267clgh.apps.googleusercontent.com';
        $token1 = 'eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjExMTg5OTk2MTY4OTk5Njc2OTI2MCIsImVtYWlsIjoiYmFnaGRhc2FyeWFuLmdldm9yZy45N0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwibmJmIjoxNzA4OTI3NDI1LCJuYW1lIjoiR2V2b3JnIEJhZ2hkYXNhcnlhbiIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS9BQ2c4b2NJdUJrczBzbDEwQldnaVdIcTd0QmE0MFVONkRxRFZKeVdCeG91SXl2cE1QQT1zOTYtYyIsImdpdmVuX25hbWUiOiJHZXZvcmciLCJmYW1pbHlfbmFtZSI6IkJhZ2hkYXNhcnlhbiIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA4OTI3NzI1LCJleHAiOjE3MDg5MzEzMjUsImp0aSI6IjM2ZWViMDIxZWVhNDBjNDc1ODQxYjgzMmU4OWQ2NGJhMzNiY2M0MzgifQ';

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
