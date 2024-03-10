<?php

namespace App\Http\Middleware\MuseumBranch;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MuseumBranchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      $administrator = request()->user();
      // dd(request()->user()->roles);
      // $user_g_name = $administrator->roles->pluck('name');

      if($administrator->hasRole('museum_admin|content_manager')) {
        // dd(444);
      //   return $user_g_name == 'admin' || $user_g_name == 'super_admin' || $user_g_name == 'web' ?  $next($request) : redirect()->back();
      }

        return $next($request);
    }
}
