<?php

namespace Activelogiclabs\Administration\Http\Controllers;

use Activelogiclabs\Administration\Admin\Core;
use Activelogiclabs\Administration\Admin\FieldComponent;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdministrationController extends Controller
{
    public $type = Core::CONTROLLER_TYPE_CRUD;
    public $model;
    public $title;
    public $icon = 'fa-chevron-right';
    public $paginationLength = 10;

    public $fieldDefinitions;
    public $overviewFields;
    public $detailGroups;
    public $titleButtons = [];

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
    public function __construct()
    {
        $uriArray = explode("\\", get_called_class());

        $this->slug = strtolower(str_replace("Controller", "", end($uriArray)));
        $this->url = Core::url($this->slug . "/overview");
        $this->class = get_called_class();

        if($this->type == Core::CONTROLLER_TYPE_CUSTOM){
            $this->url = Core::url($this->slug);
        }
    }

    public function __call($method, $parameters)
    {
        $stop = '';
    }

    /**
     * Standard Overview
     *
     * @return mixed
     */
    public function overview(Request $request)
    {
        $data = FieldComponent::buildComponents($this->model, $this->buildFields($this->overviewFields), $this->fieldDefinitions);

        $links = $data->links('administration::pagination.admin-pagination');

        return Core::view( Core::PAGE_TYPE_OVERVIEW, [
            'title' => $this->title,
            'detail_url' => Core::url($this->slug . "/detail"),
            'import_url' => Core::url($this->slug . "/import_data"),
            'export_url' => Core::url($this->slug . "/export_data"),
            'sort_url' => Core::url($this->slug . "/overview/sort"),
            'overviewFields' => $this->buildFields($this->overviewFields),
            'overviewTitleButtons' => $this->buildTitleButtons($this->titleButtons),
            'data' => $data,
            'page_links' => $links,
            'title_buttons' => $this->titleButtons
        ]);
    }

    /**
     * Detail view
     *
     * @param null $id
     * @return mixed
     */
    public function detail($id = null)
    {
        //--- New Record
        if (is_null($id)) {

            $detailGroups = $this->createRecord();

        } else {

            $detailGroups = $this->loadRecord($id);

        }

        return Core::view( Core::PAGE_TYPE_DETAIL, [
            'save_url' => Core::url($this->slug . "/save/" . $id),
            'delete_url' => Core::url($this->slug . "/delete/" . $id),
            'back_url' => Core::url($this->slug . "/overview"),
            'title' => 'Details',
            'subtitle' => $id,
            'detailGroups' => $detailGroups
        ]);
    }

    /**
     * Save Field
     *
     * @param Request $request
     * @param null $id
     * @return mixed
     */
    public function saveField(Request $request, $id = null)
    {
        $model = $this->model;
        $model = $model::findOrNew($id);

        //--- Build component
        $component = FieldComponent::buildComponent($data = $request->all(), $this->fieldDefinitions);

        $model->{$component->name} = $component->onSubmit();

        if (!$model->save() and $model->isDirty()) {

            return Core::errorResponse($data, "Failed to save record");

        }

        $responseData = [
            $primaryKey = $model->getKeyName() => $model->$primaryKey
        ];

        if (property_exists($component, 'imageUrl')) {
            $responseData['value'] = $component->getUrl();
        }

        return Core::successResponse($responseData);
    }

    /**
     * Delete Field
     *
     * @param Request $request
     * @param null $id
     * @param null $field
     * @return mixed
     */
    public function deleteField(Request $request, $id = null, $field = null)
    {
        $model = $this->model;
        $model = $model::find($id);

        $component = FieldComponent::buildComponent($data = [$field => $model->$field], $this->fieldDefinitions);

        $model->$field = $component->onDelete();

        if (!$model->save() and $model->isDirty()) {

            return Core::errorResponse($data, "Failed to save record");

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
    public function deleteRecord(Request $request, $id = null)
    {
        if (empty($id)) {

            return redirect()->back();

        }

        $model = $this->retrieveModel($id);
        $model->delete();

        return redirect(Core::url($this->slug));
    }

    /**
     * Create Record
     *
     * @return array
     */
    private function createRecord()
    {
        $model = new $this->model();

        return $this->buildDetailGroups($model);
    }

    /**
     * Load Record
     *
     * @param $id
     * @return array
     */
    private function loadRecord($id)
    {
        $model = $this->retrieveModel($id);

        return $this->buildDetailGroups($model);
    }

    /**
     * Build Detail Groups
     *
     * @param $model
     * @return array
     */
    private function buildDetailGroups($model)
    {
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
     * Build Fields
     *
     * @param array $fields
     * @return array
     */
    private function buildFields($fields = [])
    {
        if (empty($fields)) {

            $model = new $this->model();

            $columns = [];

            foreach ($model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable()) as $column) {
                $columns[$column] = ucwords(str_replace("_", " ", $column));
            }

            $hidden = $model->getHidden();
            if (!empty($hidden)) {

                foreach ($hidden as $remove) {

                    unset($columns[$remove]);

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

    /**
     * Build Default Detail Group
     *
     * @param $model
     * @return array
     */
    public function buildDefaultDetailGroup($model)
    {
        $fields = $this->buildFields();

        unset($fields[$model->getKeyName()]);

        if ($attr = $model->getAttributes()) {

            $rawData = $attr;

        } else {

            $rawData = array_fill_keys(array_keys($fields), null);
        }

        $data = FieldComponent::buildComponents($model, $fields, $this->fieldDefinitions, [$rawData]);

        $info = [
            'group_title' => "General Information",
            'group_type' => Core::GROUP_STANDARD,
            'group_fields' => $fields,
            'data' => $data
        ];

        return $info;
    }

    /**
     * Build Title Buttons
     *
     * @param $titleButtons
     */
    private function buildTitleButtons($titleButtons)
    {
        if (empty($titleButtons)) {



        }
    }

    /**
     * Build Detail Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     */
    private function buildDetailGroup($dataGroup, $model)
    {
        switch ($dataGroup['group_type']) {

            Case Core::GROUP_STANDARD:

                return $this->buildStandardDataGroup($dataGroup, $model);

                break;

            case Core::GROUP_FULL:

                return $this->buildFullDataGroup($dataGroup, $model);

                break;

            case Core::GROUP_WYSIWYG:

                return $this->buildWysiwygDataGroup($dataGroup, $model);

                break;
        }
    }

    /**
     * Build Standard Data Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     */
    public function buildStandardDataGroup($dataGroup, $model)
    {
        $modelData = $model->getAttributes();

        if (empty($modelData)) {

            $modelData = array_fill_keys(array_keys($dataGroup['group_fields']), null);

        }

        $dataGroup['data'] = FieldComponent::buildComponents($model, $this->buildFields($dataGroup['group_fields']), $this->fieldDefinitions, [$modelData]);

        return $dataGroup;
    }

    /**
     * Build Full Data Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     */
    public function buildFullDataGroup($dataGroup, $model)
    {
        $dataGroup['data'] = FieldComponent::buildComponent([$dataGroup['field'] => $model->{$dataGroup['field']}], $this->fieldDefinitions);
        return $dataGroup;
    }

    /**
     * Build WYSIWYG Data Group
     *
     * @param $dataGroup
     * @param $model
     * @return mixed
     */
    public function buildWysiwygDataGroup($dataGroup, $model)
    {
        $dataGroup['data'] = FieldComponent::buildComponent([$dataGroup['field'] => $model->{$dataGroup['field']}], $this->fieldDefinitions);
        return $dataGroup;
    }

    /**
     * Retrieve Instance of MOdel
     *
     * @param $id
     * @return mixed
     */
    private function retrieveModel($id)
    {
        $model = $this->model;
        $model = $model::findOrNew($id);

        return $model;
    }

    /**
     * Export/Stream CSV
     *
     * @return mixed
     */
    public function exportData()
    {
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$this->slug.'.csv',
            'Expires' => '0',
            'Pragma' => 'public'
        ];

        //TODO: rewrite to avoid storing large data sets in memory
        $model = new $this->model();
        $data = $model::all()->toArray();

        $callback = function() use ($data, $model) {

            if ($model->usesTimestamps()) {
                unset($data[0][$model->getKeyName()]);
                unset($data[0][$model->getCreatedAtColumn()]);
                unset($data[0][$model->getUpdatedAtColumn()]);
            }

            $out = fopen('php://output', 'w');
            fputcsv($out, array_keys($data[1]));

            foreach($data as $line) {
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