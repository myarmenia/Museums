<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\SingupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\API\AuthService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

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

            $me->avatar = $me->avatar?route('get-file', ['path' => $me->avatar]): "";

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
 
         return response()->json(['success' => false, 'message' => translateMessageApi('something-went-wrong')]);

    }
}
