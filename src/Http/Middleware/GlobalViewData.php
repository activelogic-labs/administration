<?php

namespace Activelogiclabs\Administration\Http\Middleware;

use Activelogiclabs\Administration\Admin\Core;
use Closure;

class GlobalViewData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        view()->share("navigation", Core::navigationControllers());
        view()->share("system_title", Core::getConfig('title'));
        view()->share("error", session()->get('administration_error'));
        view()->share("success", session()->get('administration_success'));

        return $next($request);
    }
}
