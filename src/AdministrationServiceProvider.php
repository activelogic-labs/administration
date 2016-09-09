<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 7/14/16
 * Time: 2:14 PM
 */

namespace Activelogiclabs\Administration;

use Activelogiclabs\Administration\Admin\Core;
use Activelogiclabs\Administration\Commands\AdminControllerMakeCommand;
use Activelogiclabs\Administration\Commands\MakeAdministrationCommand;
use Activelogiclabs\Administration\Http\Middleware\GlobalViewData;
use Illuminate\Support\ServiceProvider;

class AdministrationServiceProvider extends ServiceProvider {

    protected $commands = [
        MakeAdministrationCommand::class,
        AdminControllerMakeCommand::class,
    ];

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config' => config_path('')
        ], 'config');

        $this->publishes([
            __DIR__ . '/public' => public_path("vendor/administration")
        ], 'public');

        if (!$this->app->routesAreCached()) {
            require __DIR__.'/Http/routes.php';
        }

        $this->app['router']->middleware('globalViewData', GlobalViewData::class);

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'administration');
    }

    public function register()
    {
        if(! empty($this->commands)) {
            $this->commands($this->commands);
        }
    }

}