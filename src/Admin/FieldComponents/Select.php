<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;

class Select extends FieldComponent
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
        $viewData = [
            'name' => $this->name,
            'options' => $this->definition['options'],
            'selected' => $this->value
        ];

        return view('administration::components.select', $viewData);
    }

    public function onSubmit()
    {
        return $this->value;
    }
}