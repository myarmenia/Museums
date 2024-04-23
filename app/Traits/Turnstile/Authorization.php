<?php
namespace App\Traits\Turnstile;
use App\Models\Turnstile;
use Tymon\JWTAuth\Exceptions\JWTException;

trait Authorization
{
  public function login($data)
  {

      $credentials['name'] = $data['name'];
      $credentials['password'] = $data['password'];

    // dd($credentials);
      try {
          if (!$token = auth()->guard('turnstile')->attempt($credentials)) {
            return false;
          }
      } catch (JWTException $e) {
          return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
      }

      return $this->respondWithToken($token);
  }

  protected function respondWithToken($token)
  {

    return [
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('turnstile')->factory()->getTTL() * 60
    ];
  }


  public function register($data){

      $data['password'] = bcrypt($data['password']);

      $user = Turnstile::create($data);

      $data = [
        'id' => $user->id,
        'museum_id' => $user->museum_id,
        'name' => $user->name
      ];

      return $user ? $data : false;
  }



}
