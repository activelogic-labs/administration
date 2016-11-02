<?php
/**
 * Created by Dalton Gibbs
 * Date: 10/25/16
 * Time: 7:38 PM
 */

namespace Activelogiclabs\Administration\Admin;

use Activelogiclabs\Administration\Admin\FieldComponents\Text;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

//NOTE: Refactor to remove variable passing
trait ComponentBuilder
{
    public function buildComponentsWithFilters($model, $fields, $definitions = [], $filters = [])
    {
        return self::buildComponents($model, $fields, $definitions, $filters);
    }

    public function buildComponentsFromData($model, $fields, $definitions = [], $rawData = null)
    {
        return self::buildComponents($model, $fields, $definitions, [], $rawData);
    }

    /**
     * Builds the components for the section
     *
     * @param $model
     * @param $fields
     * @param array $definitions
     * @return mixed
     */
    public function buildComponents($model, $fields, $definitions = [], $filters = [], $rawData = null)
    {
        $dataSet = [];

        if (is_null($rawData)) {

            $paginator = self::retrieveData($model, $fields, $filters);

            foreach ($paginator as $id => $row) {

                foreach ($row->getAttributes() as $key => $value) {

                    $dataSet[$row->{$row->getKeyName()}][$key] = self::buildComponent($key, $value, $definitions);

                }

            }

            return $paginator->setCollection(collect($dataSet));

        }


        foreach ($rawData as $id => $row) {

            foreach ($row as $key => $value) {

                $dataSet[$id][$key] = self::buildComponent($key, $value, $definitions);

            }

        }

        $paginator = new LengthAwarePaginator(collect($dataSet), count($dataSet), 15);

        return $paginator;
    }

    public function buildComponent($name, $value, $definitions = [])
    {
        if(!is_string($name)){
            Throw new \Exception("Name arg must be of type string");
        }

        if (!isset($definitions[$name])) {
            return new Text($name, $value);
        }

        return new $definitions[$name]['type']($name, $value, $definitions[$name]);
    }

    /**
     * Retrieves data from model
     *
     * @param $model
     * @param $fields
     * @return mixed
     * @throws \Exception
     */
    public function retrieveData($model, $fields, $filters)
    {
        if (is_string($model)) {
            $model = new $model();
        }
//        dd(get_class_methods($model));

        //--- Get Primary Key
        $primaryKey = $model->getKeyName();

        if(!$primaryKey){
            Throw new \Exception("Primary key for model \"".get_class($model)."\" cannot be found");
        }

        //--- Always include primary key
        if (!in_array($primaryKey = $model->getKeyName(), array_keys($fields))) {
            $fields[$primaryKey] = ucfirst($primaryKey);
        }

        $query = $model->query();

        if (!empty($fields)) {
            $query = $query->select(array_keys($fields));
        }

        if (!empty($filters)) {

//            foreach ($filters as $column => $value) {
//                $filter = $this->filterable[Str::snake($column)];
//
//                if (isset($filter['scope'])) {
//
//                    if (method_exists($model, "scope" . ucfirst($filter['scope']))) {
//
//                        $query->{$filter['scope']}($value);
//
//                    } else {
//
//                        throw new FilterException("Query scope does not exist on model");
//
//                    }
//
//                    continue;
//
//                }
//
//                $query->where(Str::snake($column), $value);
//            }
            $query = $this->applyFilters($query, $model, $filters);

        }

        if (!empty($this->forcedFilters)) {

            $query = $this->applyFilters($query, $model, $this->forcedFilters);

        }

        if (!empty($this->sorts)) {

            $query = $this->applySorts($query, $model, $this->sorts);

        }

        return $query->paginate();
    }

    protected function applyFilters($query, $model, $filters)
    {
        foreach ($filters as $column => $value) {
            $filter = $this->filterable[Str::snake($column)];

            if (isset($filter['scope'])) {

                if (method_exists($model, "scope" . ucfirst($filter['scope']))) {

                    $query->{$filter['scope']}($value);

                } else {

                    throw new FilterException("Query scope does not exist on model");

                }

                continue;

            }

            $query->where(Str::snake($column), $value);
        }

        return $query;
    }

    protected function applySorts($query, $model, $sorts)
    {
        foreach ($sorts as $column => $direction) {
            $sort = $this->sortable[Str::snake($column)];

            if (isset($sort['scope'])) {

                if (method_exists($model, "scope" .  ucfirst($sort['scope']))) {

                    $query->{$sort['scope']}($direction);

                } else {

                    throw new SortException("Query scope does not exist on model");

                }

                continue;

            }

            $query->orderBy(Str::snake($column), $direction);
        }

        return $query;
    }
}

class FilterException extends \Exception {}

class SortException extends \Exception {}