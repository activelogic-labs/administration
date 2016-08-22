<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\FieldComponent;

class Text extends FieldComponent
{
    /**
     * Builds the formatted data view for the component
     *
     * @return mixed
     */
    public function dataView()
    {
        return $this->value;
    }

    /**
     * Builds the form field for the component
     *
     * @return string
     */
    public function fieldView()
    {
        return "<input type=\"text\" name=\"{$this->name}\" value=\"{$this->value}\" placeholder=\"Enter value...\">";
    }

    public function onSubmit()
    {
        return $this->value;
    }
}