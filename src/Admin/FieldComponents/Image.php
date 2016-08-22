<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/18/16
 * Time: 10:00 PM
 */

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\FieldComponent;

class Image extends FieldComponent
{
    public function dataView()
    {
        return "<img src='/images/{$this->value}'>";
    }

    public function fieldView()
    {
        //TODO: convert to file input when layout is decided
        return "<input type='text' name='{$this->name}' value='{$this->value}' >";
    }

    public function onSubmit()
    {
        return $this->value;
    }
}