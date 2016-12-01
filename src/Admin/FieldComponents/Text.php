<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;

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
        $fieldName = $this->field_name($this->name);

        return "<input type=\"text\" name=\"{$fieldName}\" value=\"{$this->value}\" placeholder=\"Enter value...\">";
    }

    public function onSubmit()
    {
        return $this->value;
    }
}