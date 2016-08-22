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
    public $icon;
    public $paginationItems;

    public $fieldDefinitions;
    public $overviewFields;
    public $detailFields;
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

        $this->slug = strtolower(str_replace("Controller", "", $uriArray[3]));
        $this->url = Core::url($this->slug . "/overview");
        $this->class = $uriArray[3];
    }

    /**
     * Standard Overview
     *
     * @return mixed
     */
    public function overview()
    {
        return Core::view( Core::PAGE_TYPE_OVERVIEW, [
            'title' => $this->title,
            'detail_url' => Core::url($this->slug . "/detail"),
            'overviewFields' => $this->buildFields($this->overviewFields),
            'overviewTitleButtons' => $this->buildTitleButtons($this->titleButtons),
            'data' => FieldComponent::buildComponents($this->model, $this->buildFields($this->overviewFields), $this->fieldDefinitions)
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
        $model = $this->retrieveModel($id);
        $rowId = isset($model->id) ? $model->id : "";

        $detailData = [];

        if (empty($this->detailFields)) {

            $detailData[] = $this->buildDefaultDetailFields($model);

        } else {

            foreach ($this->detailFields as $detailGroup) {
//                $detailGroup['data'] =  FieldComponent::buildComponents($model, $this->buildFields($detailGroup['group_fields']), $this->fieldDefinitions, [$model->toArray()]);
//                $detailData[] = $detailGroup;

                $detailData[] = $this->buildDataGroup($detailGroup, $model);
            }

        }

        return Core::view( Core::PAGE_TYPE_DETAIL, [
            'save_url' => Core::url($this->slug . "/save/" . $rowId),
            'title' => 'Details',
            'subtitle' => $rowId,
            'detailData' => $detailData
        ]);
    }

    public function saveField(Request $request, $id = null)
    {
        $model = $this->model;
        $model = $model::findOrNew($id);

        //--- Build component
        $component = FieldComponent::buildComponent($data = $request->all(), $this->fieldDefinitions);

        $model->{$component->name} = $component->onSubmit();

        if (!$model->save()) {

            return Core::errorResponse($data, "Failed to save record");

        }

        return Core::successResponse([$primaryKey = $model->getKeyName() => $model->$primaryKey]);
    }

    private function buildFields($fields)
    {
        if (empty($fields)) {

            $model = new $this->model();

            $columns = [];

            foreach ($model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable()) as $column) {
                $columns[$column] = ucwords(str_replace("_", " ", $column));
            }

            $fields = $columns;
        }

        return $fields;
    }

    public function buildDefaultDetailFields($model)
    {
        $fields = $this->buildFields([]);
        $data = FieldComponent::buildComponents($model, $fields, $this->fieldDefinitions, [$model->toArray()]);

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

    private function buildDataGroup($dataGroup, $model)
    {
        switch ($dataGroup['group_type']) {

            Case Core::GROUP_STANDARD:

                return $this->buildStandardDataGroup($dataGroup, $model);

                break;

            case Core::GROUP_FULL:

                return $this->buildFullDataGroup($dataGroup, $model);

                break;

            case Core::GROUP_WYSIWYG:



                break;
        }
    }

    public function buildStandardDataGroup($dataGroup, $model)
    {
        $dataGroup['data'] = FieldComponent::buildComponents($model, $this->buildFields($dataGroup['group_fields']), $this->fieldDefinitions, [$model->toArray()]);

        return $dataGroup;
    }

    public function buildFullDataGroup($dataGroup, $model)
    {

        return $dataGroup;
    }

    public function buildWysiwygDataGroup($dataGroup, $model)
    {

    }

    private function retrieveModel($id)
    {
        $model = $this->model;
        $model = $model::findOrNew($id);

        return $model;
    }
}