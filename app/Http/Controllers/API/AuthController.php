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

        // $token = $request->input('token');
        $token = "eyJhbGciOiJSUzI1NiIsImtpZCI6IjA4YmY1YzM3NzJkZDRlN2E3MjdhMTAxYmY1MjBmNjU3NWNhYzMyNmYiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIxNjkwMjIzODg3LTlnc3FzMmlhNnVhNzFjMmZrdXAzdnZwM2wyNjdjbGdoLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiYXVkIjoiMTY5MDIyMzg4Ny05Z3NxczJpYTZ1YTcxYzJma3VwM3Z2cDNsMjY3Y2xnaC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsInN1YiI6IjEwNzMyNzg1NDMzODA2MzYzNjE5OCIsImVtYWlsIjoidHJhZGluZ3RyYWRlckBtYWlsLnJ1IiwiZW1haWxfdmVyaWZpZWQiOnRydWUsIm5iZiI6MTcwOTU0MTYzNSwibmFtZSI6IkFybSBUcmFkZXJzIiwicGljdHVyZSI6Imh0dHBzOi8vbGgzLmdvb2dsZXVzZXJjb250ZW50LmNvbS9hL0FDZzhvY0xac3RNczJtWV9ZYi1QUkxLMVBYa29femZRb19HQkdPazk5aUFhMXZHcWtnPXM5Ni1jIiwiZ2l2ZW5fbmFtZSI6IkFybSIsImZhbWlseV9uYW1lIjoiVHJhZGVycyIsImxvY2FsZSI6InJ1IiwiaWF0IjoxNzA5NTQxOTM1LCJleHAiOjE3MDk1NDU1MzUsImp0aSI6IjdjY2I5YzMwM2JmOTI5YWQ1M2YyZjA3Yjk2OTJjYzAyNTI2MGQxMTUifQ.lsiGfCbRhjBAO9bOt7JfXDRgWwcYmXrkaAqCmuz935xP6c_PpmLIM5MWnnsgGhWxngDmPmU_bW2emQAxRityErY674ba9PAFunX5x0iFblqlnWG69tXXOBD-IK5sJI03vL48VSUwKhrxsMrwGsqghKg39Iu-uYBnRrCKf7AObJDE2ce8RuZnuEZbZLs-Dt9R7WE8D5C5Db8MFypRb8LE2QkxQcxPMKLEUqk_KpmzR-Xps_uTe3SJD3z4f_z7Ul-59tWDcbcTMEITFMpTH9gVV-bbEKK_8kCgcTUeWu5ewse2VRx9ladax6tmeyhqIkjNx4iGf83q3SU3nmEZCUKOyQ";

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
