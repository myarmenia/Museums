<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\Auth\ResendVerifyRequest;
use App\Http\Requests\SingupRequest;
use App\Models\User;
use App\Services\API\AuthService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Google_Client;

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

    public function authGoogle(Request $request)
    {

        $token = $request->input('token');

        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $payload = $client->verifyIdToken($token);

        $email = $payload['email'];

        $user = User::where('email', $email)->firstOrCreate([
            'name' => $payload['given_name'],
            'surname' => $payload['family_name'],
            'email' => $email,
            'google_id' => $payload['sub'],
            'status' => 1,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(['success' => true, 'token' => $token, 'user' => $user]);
       
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
        $dataOrError = $this->authService->checkVerifyToken($request->all());

        if($dataOrError){
            return response()->json($dataOrError);
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

    public function signupInfo(Request $request)
    {
        if(auth('api')->user()){
            $addedData = $this->authService->signupInfo($request->all());

            if($addedData){
                return response()->json(['success' => true, 'message' => translateMessageApi('user-edit'), 'user' => $addedData]);
            }
        }

        return response()->json(['error' => translateMessageApi('something-went-wrong')], 500);
    }
}
