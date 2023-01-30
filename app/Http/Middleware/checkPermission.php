<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class checkPermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $permission
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        $info = Session::get('isLogin');
        $allowed_routes = $info->allowed_routes;
        if (!in_array($permission, $allowed_routes)) {
            return redirect("permission_denied");
        }
        View::share('my_route', $permission);
        return $next($request);
    }
}
