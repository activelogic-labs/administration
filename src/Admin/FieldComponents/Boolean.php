<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;

class Boolean extends FieldComponent
{
    /**
     * Builds the formatted data view for the component
     *
     * @return mixed
     */
    public function dataView()
    {
        return $this->value ? "Yes" : "No";
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
            'value' => !empty($this->value) ? true : false
        ];

        return view('administration::components.boolean', $viewData);
    }

    public function onSubmit(){
        return $this->value ? '1' : '0';
    }
}