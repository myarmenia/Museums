<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class ModelAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $prefix = ltrim(request()->route()->getPrefix(), "/");
        $tb_name = str_replace("-", "_", $prefix);

        if (!museumAccessId()) {
            return redirect()->back();
        }
        else {

            $className = 'App\Models\\' . Str::studly(Str::singular($tb_name));

            if (class_exists($className)) {
                $model = new $className;
                $item = $model::find($request->id);
          
                if ($item != null && $item->museum_id != museumAccessId()) {
                  return redirect()->back();

                } else {
                  return $next($request);
                }
            }
        }
    }
}
