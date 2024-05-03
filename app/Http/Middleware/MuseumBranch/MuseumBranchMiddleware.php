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


      if($administrator->hasRole('museum_admin|content_manager|manager')) {

      }

        return $next($request);
    }
}
