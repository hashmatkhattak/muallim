<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Session::has('isLogin') || empty(Session::get('isLogin'))) {
            return redirect(route("login"));
        }
        return $next($request);
    }
}
