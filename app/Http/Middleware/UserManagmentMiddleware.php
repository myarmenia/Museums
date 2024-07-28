<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class UserManagmentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


      $id = request()->segment(2);
      $user = User::find($id);
      $administrator = request()->user();

      if($user != null){
        $user_g_name = $user->roles->pluck('g_name')[0];

          if ($administrator->hasRole('super_admin') && Auth::id() != $id) {
            return $user_g_name == 'admin' || $user_g_name == 'super_admin' || $user_g_name == 'web' ?  $next($request) : redirect()->back();
          }

          if ($administrator->hasRole('museum_admin')) {
            return $user_g_name == 'museum' && $administrator->hasInStaff($id) ? $next($request) : redirect()->back();
          }
      }
      else{
        return  $next($request);
      }

      return redirect()->back();
    }
}
