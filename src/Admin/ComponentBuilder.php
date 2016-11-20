<?php

namespace Activelogiclabs\Administration\Admin;

use Activelogiclabs\Administration\Admin\FieldComponents\Text;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

trait ComponentBuilder
{
    public $model;
    public $overviewFields;
    public $fieldDefinitions;
    public $filters;
    public $sorts;
    public $scopes;
    public $forcedFilters;

    /**
     * Build Overview Components
     *
     * @return array
     * @throws \Exception
     */
    public function buildOverviewComponents()
    {
        $fields = $this->overviewFields;

        if(isset($fields[0])){

            $data = [];

            foreach($fields as $key => $group_overview){

                $label = empty($fields[$key]['label']) ? null : $fields[$key]['label'];
                $caption = empty($fields[$key]['caption']) ? null : $fields[$key]['caption'];
                $model_scope = empty($fields[$key]['model_scope']) ? null : $fields[$key]['model_scope'];
                $group_fields = empty($fields[$key]['fields']) ? null : $fields[$key]['fields'];

                $paginate = true;

                if(isset($group_overview['pagination'])){
                    if($group_overview['pagination'] == false){
                        $paginate = false;
                    }
                }

                if($this->disableOverviewPagination == true){
                    $paginate = false;
                }

                $paginator = self::retrieveData($group_fields, [
                    'model_scopes' => [$model_scope],
                    'paginate' => $paginate
                ]);

                foreach ($paginator as $id => $row) {

                    $dataSet = [];

                    foreach ($row->getAttributes() as $a_key => $value) {

                        $dataSet[$row->{$row->getKeyName()}][$a_key] = self::buildComponent($a_key, $value);

                    }

                }

                $overviewComponent = new OverviewComponent();
                $overviewComponent->label = $label;
                $overviewComponent->caption = $caption;
                $overviewComponent->overviewFields = $this->buildFields($group_fields);
                $overviewComponent->pagination = false;

                if(empty($group_overview['pagination']) && $group_overview['pagination'] == true){

                    $overviewComponent->data = $paginator->setCollection(collect($dataSet));
                    $overviewComponent->pagination = $overviewComponent->data->appends($this->paginateFilters())->links('administration::pagination.admin-pagination');
                    $overviewComponent->total = $overviewComponent->data->total();

                }else{

                    $overviewComponent->data = collect($dataSet);
                    $overviewComponent->total = $overviewComponent->data->count();

                }

                $data[] = $overviewComponent;
            }

            return $data;
        }

        $paginate = $this->disableOverviewPagination ? false : true;

        $dataSet = [];
        $paginator = self::retrieveData($this->overviewFields, [
            'paginate' => $paginate
        ]);

        foreach ($paginator as $id => $row) {

            foreach ($row->getAttributes() as $key => $value) {

                $dataSet[$row->{$row->getKeyName()}][$key] = self::buildComponent($key, $value);

            }

        }

        $overviewComponent = new OverviewComponent();
        $overviewComponent->data = $dataSet;
        $overviewComponent->overviewFields = $this->buildFields($this->overviewFields);
        $overviewComponent->pagination = $paginate ? $paginator->appends($this->paginateFilters())->links('administration::pagination.admin-pagination') : false;
        $overviewComponent->total = $paginator->total();

        return [$overviewComponent];
    }

    /**
     * Build Detail View Components
     *
     * @return array
     * @throws \Exception
     */
    public function buildDetailViewComponents($rawData)
    {
        foreach ($rawData as $id => $row) {

            foreach ($row as $key => $value) {

                $dataSet[$id][$key] = self::buildComponent($key, $value, $this->fieldDefinitions);

            }

        }

        $paginator = new LengthAwarePaginator(collect($dataSet), count($dataSet), 15);

        return $paginator;
    }

    public function buildComponent($name, $value)
    {
        $definitions = $this->fieldDefinitions;

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
    public function retrieveData($_fields = [], $args = [])
    {
        if(!isset($args['paginate'])){
            $args['paginate'] = true;
        }

        $fields = $this->buildFields($_fields);

        $model = $this->model;
        $filters = $this->filters;
        $scopes = empty($args['model_scopes']) ? null : $args['model_scopes'];
        $sorts = $this->sorts;
        $forcedFilters = $this->forcedFilters;

        if (is_string($model)) {
            $model = new $model();
        }

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

        if ($fields) {
            $query = $query->select(array_keys($fields));
        }

        if ($filters) {
            $query = $this->applyFilters($query, $model, $filters);
        }

        if($scopes){
            $query = $this->applyScopes($model, $scopes);
        }

        if ($forcedFilters) {
            $query = $this->applyFilters($query, $model, $forcedFilters);
        }

        if ($sorts) {
            $query = $this->applySorts($query, $model, $sorts);
        }

        if($args['paginate'] == false){
            $records = $query->get();
            $totalRecords = $records->count();

            return new LengthAwarePaginator($records, $totalRecords, $totalRecords, 1);
        }

        return $query->paginate();
    }

    protected function applyScopes($query, $scopes)
    {
        foreach ($scopes as $scope) {

            if($scope){
                if (!method_exists($query, "scope" . ucfirst($scope))) {
                    Throw new \Exception("Query scope '" . $scope . "' does not exist on model");
                }

                $query->{Str::camel($scope)}();
            }
        }

        return $query;
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

    protected function buildFields($fields = [], $includeHiddenFields = false)
    {
        if (empty($fields)) {

            $model = new $this->model();

            $columns = [];

            foreach ($model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable()) as $column) {
                $columns[$column] = ucwords(str_replace("_", " ", $column));
            }

            if($includeHiddenFields == false){
                $hidden = $model->getHidden();
                if (!empty($hidden)) {
                    foreach ($hidden as $remove) {
                        unset($columns[$remove]);
                    }
                }
            }

            //TODO: Deal with visible fields
            if ($model->usesTimestamps()) {
                unset($columns[$model->getCreatedAtColumn()]);
                unset($columns[$model->getUpdatedAtColumn()]);
            }

            $fields = $columns;
        }

        return $fields;
    }
}

class FilterException extends \Exception {}

class SortException extends \Exception {}