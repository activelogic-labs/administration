<?php

namespace Activelogiclabs\Administration\Admin;

use Activelogiclabs\Administration\Admin\FieldComponents\Text;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class Core
{
    /**
     * Page Type Constants
     */
    const PAGE_TYPE_OVERVIEW = 'overview';
    const PAGE_TYPE_DETAIL = 'detail';
    const PAGE_TYPE_SAVE = 'save';

    /**
     * Detail Group Types
     */
    const GROUP_STANDARD = 'standard';
    const GROUP_FULL = 'full';
    const GROUP_WYSIWYG = 'wysiwyg';

    /**
     * Get Configuration Value
     *
     * @param $value
     * @return Config
     */
    public static function getConfig($value){
        return config("admin." . $value);
    }

    /**
     * Generate URL relative to admin namespace
     *
     * @param $uri
     * @return URL
     */
    public static function url($uri)
    {
        $uri = trim($uri, "/");

        if(empty(self::getConfig("base_uri"))){
            return url('/' . $uri);
        }

        return url('/' . self::getConfig("base_uri") . '/' . $uri);
    }

    /**
     * Get admin-based View
     *
     * @param $view
     * @param array $params
     * @return mixed
     */
    public static function view($view, $params = [])
    {
        $fullViewPath = 'administration::'.$view;

        if(self::getConfig("views_path")){
            $fullViewPath = 'administration::'.self::getConfig("views_path") . '.' . $view;
        }

        if(!View::exists($fullViewPath)){
            $fullViewPath = $view;
        }

        return view($fullViewPath, $params);
    }

    /**
     * Return Defined Navigation Links
     *
     * @return collection
     */
    public static function navigationControllers()
    {
        $returnArray = [];

        if(self::getConfig("navigation_controllers")){

            foreach(self::getConfig("navigation_controllers") as $section) {
                $returnArray[] = new $section();
            }

        }

        return Collection::make($returnArray);
    }

    /**
     * Return all available controllers
     *
     * @return collection
     */
    public static function controllers()
    {
        $returnArray = [];

        if (self::getConfig('controllers')) {

            foreach(self::getConfig("controllers") as $section) {
                $returnArray[] = new $section;
            }

        }

        return Collection::make($returnArray);
    }

    /**
     * Get Current Route
     *
     * @return mixed
     */
    public static function getCurrentSectionAndPage()
    {
        $route = str_replace(url(self::getConfig("base_uri")), "", URL::current());
        $route = ltrim($route, "/");

        $routeArray = explode("/", $route);

        return [
            'section' => trim($routeArray[0]),
            'page' => (!empty($routeArray[1]) ? trim($routeArray[1]) : "overview")
        ];
    }

    /**
     * Return Success Response
     *
     * @param array $data
     * @return mixed
     */
    public static function successResponse($data = [])
    {
        $success = [
            'error' => 0
        ];

        $success = array_merge($success, $data);

        return response()->json($success);
    }

    /**
     * Return Error Response
     *
     * @param $data
     * @param $message
     * @return mixed
     */
    public static function errorResponse($data, $message)
    {
        $error = [
            'error' => 1,
            'message' => $message,
            'data' => $data
        ];

        return response()->json($error);
    }

    /**
     * Send Success Response To Page
     *
     * @param $msg
     */
    public static function setSuccessResponse($msg){
        session()->flash("administration_success", $msg);
    }

    /**
     * Send Error Response To Page
     *
     * @param $msg
     */
    public static function setErrorResponse($msg){
        session()->flash("administration_error", $msg);
    }
}