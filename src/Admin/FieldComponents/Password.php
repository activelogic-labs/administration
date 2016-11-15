<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;
use Illuminate\Support\Facades\Hash;

class Password extends FieldComponent
{
    /**
     * Builds the formatted data view for the component
     *
     * @return mixed
     */
    public function dataView()
    {
        return "* * * * *";
    }

    /**
     * Builds the form field for the component
     *
     * @return string
     */
    public function fieldView()
    {
        $placeholder = "* * * * *";

        if (!empty($this->value)) {
            $placeholder = "* * * * *";
        }

        return "<input type=\"password\" name=\"{$this->name}\" placeholder=\"{$placeholder}\">";
    }

    public function onSubmit()
    {
        if($this->value){
            return bcrypt($this->value);
        }

        return null;
    }
}