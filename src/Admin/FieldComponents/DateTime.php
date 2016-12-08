<?php

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;

class DateTime extends FieldComponent
{
    const PICKER_TYPE_DATE = 'datepicker';
    const PICKER_TYPE_TIME = 'timepicker';
    const PICKER_TYPE_DATETIME = 'datetimepicker';

    private $picker_type = self::PICKER_TYPE_DATETIME;

    /**
     * DateTime constructor.
     *
     * @param null $name
     * @param null $value
     * @param array $definition
     * @param null $relationship
     */
    function __construct($name, $value, array $definition, $relationship)
    {
        parent::__construct($name, $value, $definition, $relationship);

        if(!empty($this->definition['picker_type'])){
            $this->picker_type = $this->definition['picker_type'];
        }
    }

    /**
     * Data View
     *
     * @return false|string
     */
    public function dataView()
    {
        $displayFormat = null;

        if(isset($this->definition->display_format)){

            $displayFormat = $this->definition->display_format;

        }else{

            switch($this->picker_type){

                case self::PICKER_TYPE_DATETIME :
                    $displayFormat = "m/d/y @ h:i A";
                    break;

                case self::PICKER_TYPE_DATE :
                    $displayFormat = "m/d/y";
                    break;

                case self::PICKER_TYPE_TIME :
                    $displayFormat = "h:i A";
                    break;

            }
        }

        return date($displayFormat, strtotime($this->value));
    }

    /**
     * Field View
     *
     * @return mixed
     */
    public function fieldView()
    {
        $dateValue = null;

        switch($this->picker_type){

            case self::PICKER_TYPE_DATETIME :
                $dateValue = date("F d, Y @ h:i A", strtotime($this->value));
                break;

            case self::PICKER_TYPE_DATE :
                $dateValue = date("F d, Y", strtotime($this->value));
                break;

            case self::PICKER_TYPE_TIME :
                $dateValue = date("h:i A", strtotime($this->value));
                break;

        }

        $viewData = [
            'name' => $this->name,
            'value' => $dateValue, // Same format required for DateTime Picker Plugin,
            'picker_class' => $this->picker_type
        ];

        return view('administration::components.datetime', $viewData);
    }

    /**
     * OnSubmit
     *
     * @return false|string
     */
    public function onSubmit()
    {
        $format = null;

        if(isset($this->definition->save_format)){

            $format = $this->definition->save_format;

        }else{

            switch($this->picker_type){

                case self::PICKER_TYPE_DATETIME :
                    $format = "Y-m-d H:i:s";
                    break;

                case self::PICKER_TYPE_DATE :
                    $format = "Y-m-d";
                    break;

                case self::PICKER_TYPE_TIME :
                    $format = "H:i:s";
                    break;

            }

        }

        $this->value = str_replace("@", "", $this->value);

        return date($format, strtotime($this->value));
    }
}