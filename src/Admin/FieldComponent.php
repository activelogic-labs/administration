<?php

namespace Activelogiclabs\Administration\Admin;

use Activelogiclabs\Administration\Admin\FieldComponents\Text;
use Illuminate\Database\Eloquent\Model;

abstract class FieldComponent
{
    public $name;
    public $label;
    public $value;
    public $definition;

    /**
     * FieldComponent constructor.
     *
     * @param null $name
     * @param null $value
     * @param array $definition
     */
    public function __construct($name = null, $value = null, $definition = [])
    {
        $this->name = $name;
        $this->value = $value;
        $this->definition = $definition;
    }

    abstract function dataView();
    abstract function fieldView();
    abstract function onSubmit();

    /**
     * Builds the view data for section
     *
     * @param $model
     * @param $fields
     * @param $definitions
     * @return mixed
     */
    public static function dataViews($model, $fields, $definitions)
    {
        $data = self::buildComponents($model, $fields, $definitions);

        $viewSet = [];

        foreach ($data as $key => $row) {

            foreach ($row as $id => $value) {

                $viewSet[$key][$id] = $value->dataView();

            }

        }

        return collect($viewSet);
    }

    /**
     * Builds the component fields for the section
     *
     * @param $model
     * @param $fields
     * @param $definitions
     * @return mixed
     */
    public static function fieldViews($model, $fields, $definitions)
    {
        $data = self::buildComponents($model, $fields, $definitions);

        $viewSet = [];

        foreach ($data as $key => $row) {

            foreach ($row as $id => $value) {

                $viewSet[$key][$id] = $value->fieldView();

            }

        }

        return collect($viewSet);
    }

    /**
     * Builds the compoents for the section
     *
     * @param $model
     * @param $fields
     * @param array $definitions
     * @return mixed
     */
    public static function buildComponents($model, $fields, $definitions = [], $rawData = null)
    {
        if (empty($rawData)) {

            $rawData = self::retrieveData($model, $fields);

        }

        $dataSet = [];

        foreach ($rawData as $id => $row) {

            foreach ($row as $key => $value) {

                $dataSet[$id][$key] = self::buildComponent([$key => $value], $definitions);

            }

        }

        return collect($dataSet);
    }

    public static function buildComponent($data = [], $definitions = [])
    {
        if (!isset($definitions[key($data)])) {

            return new Text(key($data), current($data));

        }

        return new $definitions[key($data)]['type'](key($data), current($data));
    }

    /**
     * Retrieves the data for a section
     *
     * TODO: Refactor to allow pagination
     *
     * @param $model
     * @param $fields
     * @return mixed
     */
    public static function retrieveData($model, $fields)
    {
        if (is_string($model)) {

            $model = new $model();

        }

        //--- Always include primary key
        if (!in_array($primaryKey = $model->getKeyName(), array_keys($fields))) {

            $fields[$primaryKey] = ucfirst($primaryKey);

        }

        $query = $model->query();

        if (!empty($fields)) {

            $query = $query->select(array_keys($fields));

        }

        //--- Set array key as row's primary key
        $keyedArray = [];
        foreach ($query->get() as $key => $value) {

            $keyedArray[$value->$primaryKey] = $value->toArray();

        }

        return $keyedArray;
    }
}