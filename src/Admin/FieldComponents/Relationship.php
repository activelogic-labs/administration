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

        return isset($related->{$this->definition['display']}) ? $related->{$this->definition['display']} : '';
    }

    public function fieldView()
    {
        $related = new $this->definition['model']();

        if(isset($this->definition['scope'])){

            $options = $related->{$this->definition['scope']}()->get();

        }else{

            $options = $related->get();

        }

        $optionsArray = [];
        $foreignKey = isset($this->definition['foreign_key']) ? $this->definition['foreign_key'] : "id";

        foreach($options as $option){
            $optionsArray[$option->$foreignKey] = $this->formatValueDisplay($option);
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

    /**
     * Extract Display Variables
     *
     * @param $value
     */
    private function formatValueDisplay($option)
    {
        if(strpos($this->definition['display'], '$') !== false){

            preg_match_all('/\$[a-zA-Z0-9_]+/', $this->definition['display'], $matches);

            $optionValue = $this->definition['display'];

            foreach($matches[0] as $match){

                $optionVariable = trim( str_replace('$', "", $match) );

                $optionValue = str_replace($match, $option->{$optionVariable}, $optionValue);

            }

            return $optionValue;
        }

        return $option->{$this->definition['display']};
    }
}