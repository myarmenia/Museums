<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckHaveMuseum
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $museumId = getAuthMuseumId();
        if(!$museumId) {
            session(['errorMessage' => 'Նախ ստեղծեք թանգարան']);
            return redirect()->route('create-museum');
        }
        
        return $next($request);
    }
}
