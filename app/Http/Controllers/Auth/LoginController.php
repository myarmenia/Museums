<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Traits\Hdm\CashierTrait;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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

  use AuthenticatesUsers, CashierTrait;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected function authenticated(Request $request, $user)
  {

    if ($user->status) {
      if ($user->isAdmin()) {

        return redirect('/welcome');

        // if ($user->isAdmin() == "admin") {
        //     return redirect('/');
        // }
        // if ($user->isAdmin() == "museum") {

        //     return redirect('/museum-dashboard');
        // }

      } else {

        Auth::logout();
        return redirect('/');
      }

    } else {

      session(['errorMessage' => 'Ձեր հաշիվն ապաակտիվացված է:']);
      Auth::logout();
      return redirect('/');
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
