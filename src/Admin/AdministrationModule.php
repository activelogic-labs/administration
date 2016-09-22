<?php

namespace Activelogiclabs\Administration\Admin;

use Illuminate\Support\Str;

abstract class AdministrationModule
{
    /*
     * Constants
     */
    const TYPE_RING_GRAPH = 'ring';
    const TYPE_NUMBER = 'number';

    /*
     * iVars
     */
    public $title = "Module Title";
    public $type = self::TYPE_NUMBER;
    public $ring_percentage;
    public $ring_primary_color = "#D0021B";
    public $ring_secondary_color = "#E0E0E0";

    public $number_constant;

   /*
    * Required CALC method
    */
    abstract public function calculate();

    /*
     * Generate command (called automatically)
     */
    public function generate()
    {
        $data = false;
        $options = [];

        if($this->type == self::TYPE_RING_GRAPH){
            if(is_null($this->ring_percentage)){
                Throw new \Exception(get_called_class() . ": A ring percentage (ring_percentage) must be set to use the ring graph module");
            }

            $data = $this->ring_percentage;
            $options = [
                'primary_color' => $this->ring_primary_color,
                'secondary_color' => $this->ring_secondary_color,
            ];
        }

        if($this->type == self::TYPE_NUMBER){
            if(is_null($this->number_constant)){
                Throw new \Exception(get_called_class() . ": A number constant (number_constant) must be set to use the number module");
            }

            $data = $this->number_constant;
        }

        return view("administration::modules." . $this->type, [
            'title' => $this->title,
            'data' => $data,
            'slug' => Str::slug($this->title),
            'options' => $options
        ]);
    }
}