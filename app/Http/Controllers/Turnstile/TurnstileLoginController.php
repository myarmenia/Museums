<?php

namespace App\Http\Controllers\Turnstile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class TurnstileLoginController extends Controller
{
  public function login(Request $request)
  {
    $credentials = $request->only('name', 'password');
    try {
      if (!$token = auth()->guard('turnstile')->attempt($credentials)) {
        return response()->json(['success' => false, 'error' => 'Some Error Message'], 401);
      }
    } catch (JWTException $e) {
      return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
    }
    return $this->respondWithToken($token);
  }

  protected function respondWithToken($token)
  {
    return response()->json([
      'success' => true,
      'data' => [
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
      ]
    ], 200);
  }
}
