<?php

namespace Activelogiclabs\Administration\Http\Controllers;

use Activelogiclabs\Administration\Admin\ComponentBuilder;
use Activelogiclabs\Administration\Admin\Core;
use Activelogiclabs\Administration\Admin\DetailComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdministrationController extends Controller {

    use ComponentBuilder;

    public $title;

    public $icon                      = 'fa-chevron-right';

    public $paginationLength          = 10;

    public $disableOverviewPagination = false;

    public $modules;

    public $detailGroups;

    public $titleButtons              = [];

    public $filterable                = [];

    public $sortable                  = [];

    public $enableAddingRecords       = true;

    public $enableExportingRecords    = true;

    public $enableDetailView          = true;

    public $linkOut;

    /**
     * System Definitions
     *
     * @var
     */
    public $slug;

    public $url;

    public $class;

    public $routes;

    /**
     * Section constructor.
     */
    public function __construct() {

        $uriArray = explode("\\", get_called_class());

        $this->slug = strtolower(str_replace("Controller", "", end($uriArray)));
        $this->url  = Core::url($this->slug);

        if ($this->linkOut) {
            $this->url = $this->linkOut;
        }

        $this->class = get_called_class();
    }

    /**
     * Standard Overview
     *
     * @return mixed
     */
    public function index(Request $request) {

        if ($request->has('filters')) {
            foreach ($request->input('filters') as $column => $value) {
                $this->filters[$column] = $value;
            }
        }

        if ($request->has('sorts')) {
            foreach ($request->input('sorts') as $column => $direction) {
                $this->sorts[$column] = $direction;
            }
        }

        return Core::view(Core::PAGE_TYPE_OVERVIEW, [
            'title' => $this->title,
            'model' => $this->model,
            'detail_url' => Core::url($this->slug . "/detail"),
            'import_url' => Core::url($this->slug . "/import_data"),
            'export_url' => Core::url($this->slug . "/export_data"),
            'sort_url' => Core::url($this->slug . "/overview/sort"),
            'filterable' => $this->filterable,
            'filters' => $this->filters,
            'sortable' => $this->sortable,
            'sorts' => $this->sorts,
            'dataset' => $this->buildOverviewComponents(),
            'title_buttons' => $this->titleButtons,
            'enable_adding_records' => $this->enableAddingRecords,
            'enable_exporting_records' => $this->enableExportingRecords,
            'enableDetailView' => $this->enableDetailView,
        ]);
    }

    public function paginateFilters() {

        $formatted = [];

        if ($this->filters) {

            foreach ($this->filters as $column => $value) {
                $formatted["filters[$column]"] = $value;
            }

            return $formatted;

        }
    }

    /**
     * Detail view
     *
     * @param null $id
     * @return mixed
     */
    public function detail($id = null) {

        $isNewRecord = false;

        //--- New Record
        if (is_null($id)) {
            $model        = new $this->model();
            $detailGroups = $this->buildDetailGroups($model);
            $isNewRecord  = true;
        } else {
            $model        = $this->retrieveModel($id);
            $detailGroups = $this->buildDetailGroups($model);
        }

        return Core::view(Core::PAGE_TYPE_DETAIL, [
            'save_url' => Core::url($this->slug . "/save/" . $id),
            'delete_url' => Core::url($this->slug . "/delete/" . $id),
            'back_url' => Core::url($this->slug),
            'title' => 'Details',
            'subtitle' => "Edit Record #" . $id,
            'detailGroups' => $detailGroups,
            'model' => $this->model,
            'isNewRecord' => $isNewRecord
        ]);
    }

    /**
     * Save
     *
     * @param Request $request
     * @return mixed
     */
    public function saveForm(Request $request) {

        try {

            $model = new $this->model;

            if ($request->id) {
                $model = $model::find($request->id);
            }

            foreach ($this->buildFields() as $field => $label) {

                $component   = $this->buildComponent($field, $request->$field, $this->fieldDefinitions);
                $submitValue = $component->onSubmit();

                if ($submitValue != null) {
                    $model->$field = $submitValue;
                }

            }

            $model->save();

            Core::setSuccessResponse("Object #" . $model->id . " has been saved");

            return redirect($this->url . "/detail/" . $model->id);

        } catch (\Exception $e) {

            return redirect()->back()->withErrors($e->getMessage());

        }
    }

    /**
     * Delete Field
     *
     * @param Request $request
     * @param null $id
     * @param null $field
     * @return mixed
     */
    public function deleteField(Request $request, $id = null, $field = null) {

        $model = $this->model;
        $model = $model::find($id);

        $component = $this->buildComponent($field, $model->$field, $this->fieldDefinitions);

        $model->$field = $component->onDelete();

        if (!$model->save() and $model->isDirty()) {
            return Core::errorResponse($field, "Failed to save record");
        }

        $responseData = [
            $primaryKey = $model->getKeyName() => $model->$primaryKey
        ];

        Core::successResponse($responseData);
    }

    /**
     * Delete Record
     *
     * @param Request $request
     * @param null $id
     * @return mixed
     */
    public function deleteRecord(Request $request, $id = null) {

        if (empty($id)) {
            return redirect()->back();
        }

        $model = $this->retrieveModel($id);
        $model->delete();

        Core::setSuccessResponse("Object id " . $id . " was deleted success");

        return redirect(Core::url($this->slug));
    }

    /**
     * Build Detail Groups
     *
     * @param $model
     * @return array
     */
    private function buildDetailGroups($model) {

        $detailGroups = [];

        if (empty($this->detailGroups)) {

            $detailGroups[] = $this->buildDefaultDetailGroup($model);

        } else {

            foreach ($this->detailGroups as $detailGroup) {

                $detailGroups[] = $this->buildDetailGroup($detailGroup, $model);

            }
        }

        return $detailGroups;
    }

    /**
     * Build Default Detail Group
     *
     * @param $model
     * @return array
     */
    public function buildDefaultDetailGroup($model) {

        $fields = $this->buildFields();

        unset($fields[$model->getKeyName()]);

        if ($attr = $model->getAttributes()) {

            $raw_data = $attr;

        } else {

            $raw_data = array_fill_keys(array_keys($fields), null);

        }

        $detailComponent         = new DetailComponent();
        $detailComponent->type   = Core::GROUP_STANDARD;
        $detailComponent->fields = $fields;
        $detailComponent->data   = $this->buildDetailViewComponents([$raw_data]);

        return $detailComponent;
    }

    /**
     * Build Detail Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     */
    private function buildDetailGroup($dataGroup, $model) {

        switch ($dataGroup['group_type']) {

            Case Core::GROUP_STANDARD:
                return $this->buildStandardDataGroup($dataGroup, $model);
                break;

            Case Core::GROUP_RELATIONSHIP:
                return $this->buildStandardDataGroup($dataGroup, $model);
                break;

            case Core::GROUP_FULL:
                return $this->buildFullDataGroup($dataGroup, $model);
                break;

            case Core::GROUP_WYSIWYG:
                return $this->buildWysiwygDataGroup($dataGroup, $model);
                break;

            case Core::GROUP_MANY:
                return $this->buildManyDataGroup($dataGroup, $model);
                break;

        }
    }

    /**
     * Build "MANY" Data Group
     * Polymorphic relationships, many-to-many, one-to-many, etc...
     * Relationship would be defined in the model
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     * @throws \Exception
     */
    public function buildManyDataGroup($dataGroup, $model) {

        if (empty($dataGroup['group_title'])) {
            Throw new \Exception("You must define a 'group_title' in your configuration for this detail group.");
        }

        if (empty($dataGroup['fields'])) {
            Throw new \Exception("You must define an array of 'fields' in your configuration for this detail group.");
        }

        $controller                            = new $dataGroup['relationship_controller']();
        $controller->overviewFields            = $dataGroup['fields'];
        $controller->disableOverviewPagination = true;

        $detailComponent             = new DetailComponent();
        $detailComponent->label      = $dataGroup['group_title'];
        $detailComponent->type       = Core::GROUP_MANY;
        $detailComponent->fields     = $dataGroup['fields'];
        $detailComponent->controller = $controller;
        $detailComponent->data       = $controller->buildOverviewComponents();

        return $detailComponent;
    }

    /**
     * Build Standard Data Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     */
    public function buildStandardDataGroup($dataGroup, $model) {

        $modelData = [];

        if (isset($dataGroup['relationship'])) {

            if ($model->{$dataGroup['relationship']} != null) {
                $modelData = $model->{$dataGroup['relationship']}->getAttributes();
            }

        } else {

            $modelData = $model->getAttributes();

        }

        if (empty($modelData)) {

            $modelData = array_fill_keys(array_keys($dataGroup['group_fields']), null);

        }

        $detailComponent               = new DetailComponent();
        $detailComponent->type         = Core::GROUP_STANDARD;
        $detailComponent->label        = $dataGroup['group_title'];
        $detailComponent->fields       = $dataGroup['group_fields'];
        $detailComponent->relationship = null;

        if (isset($dataGroup['relationship'])) {
            $detailComponent->type         = Core::GROUP_RELATIONSHIP;
            $detailComponent->relationship = $dataGroup['relationship'];
        }

        $detailComponent->data = $this->buildDetailViewComponents([$modelData], $detailComponent->relationship);

        return $detailComponent;
    }

    /**
     * Build Full Data Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     *
     * @ToDo: I don't think this works... It expects one field but an array is commonly passed back. Need to re-think this.
     */
    public function buildFullDataGroup($dataGroup, $model) {

        $detailComponent       = new DetailComponent();
        $detailComponent->type = Core::GROUP_FULL;
        $detailComponent->data = $this->buildComponent($dataGroup['field'], $model->{$dataGroup['field']}, $this->fieldDefinitions);

        return $detailComponent;
    }

    /**
     * Build WYSIWYG Data Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     */
    public function buildWysiwygDataGroup($dataGroup, $model) {

        $detailComponent       = new DetailComponent();
        $detailComponent->type = Core::GROUP_WYSIWYG;
        $detailComponent->data = $this->buildComponent($dataGroup['field'], $model->{$dataGroup['field']}, $this->fieldDefinitions);

        return $dataGroup;
    }

    /**
     * Retrieve Instance of MOdel
     *
     * @param $id
     * @return mixed
     */
    private function retrieveModel($id) {

        $model = $this->model;
        $model = $model::findOrNew($id);

        return $model;
    }

    /**
     * Export/Stream CSV
     *
     * @return mixed
     */
    public function exportData() {

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $this->slug . '.csv',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        //TODO: rewrite to avoid storing large data sets in memory
        $model = new $this->model();
        $data  = $model::all();

        if ($data->count() == 0) {
            return redirect()->back()->withErrors("There is no data to export");
        }

        $data = $data->toArray();

        $callback = function () use ($data, $model) {

            if ($model->usesTimestamps()) {
                unset($data[0][$model->getKeyName()]);
                unset($data[0][$model->getCreatedAtColumn()]);
                unset($data[0][$model->getUpdatedAtColumn()]);
            }

            $out = fopen('php://output', 'w');
            fputcsv($out, array_keys($data[1]));

            foreach ($data as $line) {
                if ($model->usesTimestamps()) {
                    unset($line[$model->getKeyName()]);
                    unset($line[$model->getCreatedAtColumn()]);
                    unset($line[$model->getUpdatedAtColumn()]);
                }

                fputcsv($out, $line);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}