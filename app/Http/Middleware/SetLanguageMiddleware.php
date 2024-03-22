<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLanguageMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $language = $request->header('Accept-Language') ?? 'am';
        session(['languages' => $language]);
        app()->setLocale(session('languages'));

        return $next($request);
    }
}
