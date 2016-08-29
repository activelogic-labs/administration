<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;

class Date extends FieldComponent
{
    public function dataView(){
        $format = empty($this->definition['display_format']) ? "m/d/y @ h:i A" : $this->definition['display_format'];
        return date($format, strtotime($this->value));
    }

    public function fieldView(){
        $format = empty($this->definition['display_format']) ? "m/d/y @ h:i A" : $this->definition['display_format'];
        return "<input type='text' name='{$this->name}' value='".date($format, strtotime($this->value))."'>";
    }

    public function onSubmit(){
        $format = isset($this->definition->save_format) ? $this->definition->save_format : "Y-m-d H:i:s";
        return date($format, strtotime($this->value));
    }
}