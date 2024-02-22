<?php

namespace App\Http\Middleware\Museum;

use App\Models\Museum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MuseumEditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($id = haveMuseum()) {
            if ($id == $request->id) {
                return $next($request);
            }
        }

        abort(403);
        // if (haveMuseumAdmin() && haveMuseum()) {
        //     $id = Museum::where('user_id', auth()->id())->first()->id;
        //     return redirect()->route('museum.edit', ['id' => (int) $id]);
        // }elseif (haveMuseumAdmin() && !haveMuseum()) {
        //     return redirect()->route('create-museum');
        // }else{
        //     return redirect()->route('museum');
        // };


    }
}
