<?php

namespace App\Http\Middleware\Museum;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MuseumMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!haveMuseumAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        
        return $next($request);
    }
}
