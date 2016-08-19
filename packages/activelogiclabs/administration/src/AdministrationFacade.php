<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 7/14/16
 * Time: 3:40 PM
 */

namespace Activelogiclabs\Administration;

use Illuminate\Support\Facades\Facade;

class AdministrationFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'activelogiclabs-administration';
    }
}