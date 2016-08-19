<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/15/16
 * Time: 10:50 AM
 */

namespace Activelogiclabs\Administration\Core;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class Core
{
    /**
     * Page Type Constants
     */
    const PAGE_TYPE_OVERVIEW = 'overview';
    const PAGE_TYPE_DETAIL = 'detail';

    /**
     * Get Configuration Value
     *
     * @param $value
     * @return Config
     */
    public static function getConfig($value)
    {
        return config("admin." . $value);
    }

    /**
     * Generate Section Link
     *
     * @param Section $section
     * @param string $page_type
     * @param array $params
     * @return string
     */
    public static function generate_link($section, $page_type = self::PAGE_TYPE_OVERVIEW, $params = [])
    {
        return url('/' . self::getConfig("base_uri") . '/' . $section->slug . '/' . $page_type);
    }

    /**
     * Get admin-based View
     *
     * @param $view
     * @param array $params
     * @return mixed
     */
    public static function view($view, $params = []){
        return view(self::getConfig("views_path") . '.' . $view, $params);
    }

    /**
     * Return Defined Navigation Links
     *
     * @return collection
     */
    public static function buildNavigationLinks()
    {
        $returnArray = [];

        if ($sections = self::getConfig('sections')) {

            foreach($sections as $section) {
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
            'page' => (!empty(trim($routeArray[1])) ? trim($routeArray[1]) : "overview")
        ];
    }
}