<?php

namespace Activelogiclabs\Administration\Admin;

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
        LaravelRoute::group(['prefix' => Core::getConfig('base_uri')], function() {

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

                if ($controller->type == Core::CONTROLLER_TYPE_CRUD) {

                    /**
                     * Default Routes
                     */
                    // dd($controller->class);
                    LaravelRoute::get($controller->slug . "/overview", $controller->class . "@overview");
                    LaravelRoute::get($controller->slug . "/detail/{id?}", $controller->class . "@detail");
                    LaravelRoute::post($controller->slug . "/save/{id?}", $controller->class . "@saveField");

                    /**
                     * Custom Routes
                     */
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

                else{

                    /**
                     * Custom Controller Index
                     */
                    LaravelRoute::get($controller->slug, $controller->class . "@index");

                    /**
                     * Custom Controller Routes
                     */
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

            }

            /**
             * Authorization Routes
             */
            LaravelRoute::auth();
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