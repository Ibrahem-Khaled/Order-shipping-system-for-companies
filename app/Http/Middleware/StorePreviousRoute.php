<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class StorePreviousRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $currentRouteName = Route::currentRouteName();

        // إذا لم يكن الراوت الحالي نفس الراوت المخزن
        if (Session::get('previous_route') !== $currentRouteName) {
            Session::put('previous_route', $currentRouteName);
        }

        return $next($request);
    }
}
