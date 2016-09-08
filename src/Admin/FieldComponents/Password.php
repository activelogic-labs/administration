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
        return "<input type=\"password\" name=\"{$this->name}\" placeholder=\"Enter to reset password...\">";
    }

    public function onSubmit()
    {
        if($this->value){
            return Hash::make($this->value);
        }

        return false;
    }
}