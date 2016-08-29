<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 7/14/16
 * Time: 2:17 PM
 */

namespace Activelogiclabs\Administration\Http\Controllers;

use Activelogiclabs\Administration\Admin\Core;
use Activelogiclabs\Administration\Admin\FieldComponent;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;


class AdministrationController extends Controller
{
    /**
     * Required Definitions
     *
     * @var
     */
    public $type = Core::CONTROLLER_TYPE_CRUD;
    public $model;
    public $title;
    public $icon = 'fa-chevron-right';
    public $paginationLength = 10;

    public $fieldDefinitions;
    public $overviewFields;
    public $detailGroups;
    public $titleButtons;

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
            'sort_url' => Core::url($this->slug . "/overview/sort"),
            'overviewFields' => $this->buildFields($this->overviewFields),
            'overviewTitleButtons' => $this->buildTitleButtons($this->titleButtons),
            'data' => $data,
            'page_links' => $links
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

        return Core::successResponse([$primaryKey = $model->getKeyName() => $model->$primaryKey]);
    }

    public function deleteRecord(Request $request, $id = null)
    {
        if (empty($id)) {

            return redirect()->back();

        }

        $model = $this->retrieveModel($id);
        $model->delete();

        return redirect(Core::url($this->slug . "/overview"));
    }

    private function createRecord()
    {
        $model = new $this->model();

        return $this->buildDetailGroups($model);
    }

    private function loadRecord($id)
    {
        $model = $this->retrieveModel($id);

        return $this->buildDetailGroups($model);
    }

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

    private function buildTitleButtons($titleButtons)
    {
        if (empty($titleButtons)) {



        }
    }

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

    public function buildStandardDataGroup($dataGroup, $model)
    {
        $modelData = $model->getAttributes();

        if (empty($modelData)) {

            $modelData = array_fill_keys(array_keys($dataGroup['group_fields']), null);

        }

        $dataGroup['data'] = FieldComponent::buildComponents($model, $this->buildFields($dataGroup['group_fields']), $this->fieldDefinitions, [$modelData]);

        return $dataGroup;
    }

    public function buildFullDataGroup($dataGroup, $model)
    {
        $dataGroup['data'] = FieldComponent::buildComponent([$dataGroup['field'] => $model->{$dataGroup['field']}], $this->fieldDefinitions);
        return $dataGroup;
    }

    public function buildWysiwygDataGroup($dataGroup, $model)
    {
        $dataGroup['data'] = FieldComponent::buildComponent([$dataGroup['field'] => $model->{$dataGroup['field']}], $this->fieldDefinitions);
        return $dataGroup;
    }

    private function retrieveModel($id)
    {
        $model = $this->model;
        $model = $model::findOrNew($id);

        return $model;
    }
}