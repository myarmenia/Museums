<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles authenticating users for the application and
  | redirecting them to your home screen. The controller uses a trait
  | to conveniently provide its functionality to your applications.
  |
  */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */

  // protected $redirectTo = RouteServiceProvider::HOME;
  protected function redirectTo()
  {
    // dd(88);
    $user = Auth::user();

    if ($user->status) {
      if(Auth::user()->isAdmin() ){
        return "/";
      }else{

        Auth::logout();
        return '/';
      }

    } else {
      // dd(999);


      session(['errorMessage' => 'Ձեր հաշիվն ապաակտիվացված է:']);
      Auth::logout();
      return '/';
    }

  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {

    $this->middleware('guest')->except('logout');
  }
}
