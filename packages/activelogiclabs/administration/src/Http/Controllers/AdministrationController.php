<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 7/14/16
 * Time: 2:17 PM
 */

namespace Activelogiclabs\Administration\Http\Controllers;

use Activelogiclabs\Administration\Core\Core;
use Illuminate\Routing\Controller;

class AdministrationController extends Controller
{

    public $title;
    public $icon;
    public $overview_page = "admin.overview";
    public $details_page = "admin.details";
    public $slug;
    public $url;
    public $controllerClass;

    /**
     * Section constructor.
     */
    public function __construct()
    {
        $uriArray = explode("\\", get_called_class());
        $this->slug = str_replace("Controller", "", $uriArray[3]);
        $this->url = Core::generate_link($this, Core::PAGE_TYPE_OVERVIEW);
        $this->controllerClass = $uriArray[3];
    }

    function overview(){
        return Core::view( Core::PAGE_TYPE_OVERVIEW );
    }

    function detail(){
        return Core::view( Core::PAGE_TYPE_DETAIL );
    }


    public function index()
    {
        return view('active-admin::dashboard');
    }
    
}