<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\FieldComponent;

class Date extends FieldComponent
{
    public function dataView()
    {
        $format = empty($this->definition['format']) ? "m/d/y @ h:i A" : $this->definition['format'];
        return date($format, strtotime($this->value));
    }

    public function fieldView()
    {
        return "<input type='datetime' name='{$this->name}' value='{$this->value}'>";
    }

    public function onSubmit()
    {
        // TODO: Implement onSubmit() method.
    }
}