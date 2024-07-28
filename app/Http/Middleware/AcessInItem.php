<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AcessInItem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $parameter): Response
    {

        $className = $parameter;

        if(class_exists($className)) {

          $model = new $className;
          $item = $model->where(['id' => $request->id, 'museum_id' => museumAccessId()])->first();

          return $item != null ? $next($request) : null;

        }

        return null;
    }
}
