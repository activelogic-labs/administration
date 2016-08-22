<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/18/16
 * Time: 9:00 PM
 */

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;

class Wysiwyg extends FieldComponent
{
    public function dataView()
    {
        return $this->value;
    }

    public function fieldView()
    {
        return "<textarea name=\"{$this->name}\" id=\"{$this->name}_wysiwyg\" wysiwyg='true'>{$this->value}</textarea>";
    }

    public function onSubmit()
    {
        return $this->value;
    }
}