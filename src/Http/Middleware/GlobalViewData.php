<?php

namespace Activelogiclabs\Administration\Http\Middleware;

use Activelogiclabs\Administration\Admin\Core;
use Closure;
use Illuminate\Support\Facades\Route;

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
        view()->share('modules', $this->generateModules($request));

        return $next($request);
    }

    /*
     * Generate Modules
     * Pass the module data to all available views
     */
    private function generateModules($request)
    {
        $controllerClassArray = explode('@', Route::currentRouteAction());
        $controllerClass = $controllerClassArray[0];

        $controller = new $controllerClass;

        if(is_array($controller->modules)){
            $generatedModuleArray = [];

            foreach($controller->modules as $module){
                $m = new $module;
                $m->calculate();

                $generatedModuleArray[] = $m->generate();
            }

            return $generatedModuleArray;
        }

        return false;
    }
}
