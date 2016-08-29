<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/25/16
 * Time: 1:41 PM
 */

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;
use Illuminate\View\View;

class Relationship extends FieldComponent
{
    public function dataView()
    {
        $related = new $this->definition['model']();

        $related = $related->where(isset($this->definition['foreign_key']) ? $this->definition['foreign_key'] : 'id', $this->value)->first();

        return $related->{$this->definition['display']};
    }

    public function fieldView()
    {
        $related = new $this->definition['model']();
        $options = $related->get();

        $optionsArray = [];
        $foreignKey = isset($this->definition['foreign_key']) ? $this->definition['foreign_key'] : "id";
        $foreignValue = $this->definition['display'];

        foreach($options as $option){
            $optionsArray[$option->$foreignKey] = $option->$foreignValue;
        }

        $viewData = [
            'name' => $this->name,
            'options' => $optionsArray,
            'selected' => $this->value
        ];

        return view('administration::components.select', $viewData);
    }

    public function onSubmit()
    {
        return $this->value;
    }
}