<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/19/16
 * Time: 11:18 AM
 */

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;

class Label extends FieldComponent
{
    public function dataView(){
        return $this->value;
    }

    public function fieldView(){
        return "<span>".$this->value."</span>";
    }

    public function onSubmit(){
        return $this->value;
    }
}