<?php

namespace Activelogiclabs\Administration\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route as LaravelRoute;

/**
 * Route Object
 *
 * Class RouteObject
 * @package App\Admin
 */
class RouteObject
{
    public $type;
    public $name;
    public $controllerFunction;

    public function  __construct($type, $name, $controllerFunction)
    {
        $this->type = $type;
        $this->name = $name;
        $this->controllerFunction = $controllerFunction;
    }
}

/**
 * Custom Route Methods
 *
 * Class Route
 * @package App\Admin
 */
class Route
{
    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';

    public static function admin()
    {
        $middleware = ['web', 'globalViewData'];

        if(Core::getConfig('enable_auth_middleware')){
            $middleware[] = 'auth';
        }

        LaravelRoute::group(['prefix' => Core::getConfig('base_uri'), 'middleware'=>$middleware], function() {

            /**
             * Default Controller
             */
            if (Core::getConfig("default_controller_route")) {
                LaravelRoute::get('/', Core::getConfig("default_controller_route"));
            }

            /**
             * Controller Based Routes
             */
            foreach(Core::controllers() as $controller) {

                // Default Routes
                LaravelRoute::get($controller->slug, $controller->class . "@index");
                LaravelRoute::get($controller->slug . "/detail/{id?}", $controller->class . "@detail");
                LaravelRoute::post($controller->slug . "/save/{id?}", $controller->class . "@saveForm");
                LaravelRoute::get($controller->slug . "/delete/{id}", $controller->class . "@deleteRecord");
                LaravelRoute::get($controller->slug . "/delete/{id}/{field}", $controller->class . "@deleteField");
                LaravelRoute::get($controller->slug . "/export_data", $controller->class . "@exportData");

                // Custom Routes
                if (!empty($controller->routes)) {

                    foreach ($controller->routes as $customRoute) {

                        switch ($customRoute->type) {

                            case Route::TYPE_GET:
                                LaravelRoute::get($controller->slug . "/" . $customRoute->name, $customRoute->controllerFunction);
                                break;

                            case Route::TYPE_POST:
                                LaravelRoute::post($controller->slug . "/" . $customRoute->name, $customRoute->controllerFunction);
                                break;
                        }

                    }

                }

            }
        });

    }

    /**
     * Create GET Route
     *
     * @param $name
     * @param $controller
     * @param $function
     * @return array
     */
    public static function get($name, $controllerFunction){
        return self::Create(self::TYPE_GET, $name, $controllerFunction);
    }

    /**
     * Create POST Route
     *
     * @param $name
     * @param $controller
     * @param $function
     * @return array
     */
    public static function post($name, $controllerFunction){
        return self::Create(self::TYPE_POST, $name, $controllerFunction);
    }

    /**
     * Create Route
     *
     * @param string $type
     * @param $name
     * @param $controller
     * @param $function
     * @return array
     */
    private static function create($type = self::TYPE_GET, $name, $controllerFunction){
        return new RouteObject($type, $name, $controllerFunction);
    }
}