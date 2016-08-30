<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/18/16
 * Time: 10:00 PM
 */

namespace Activelogiclabs\Administration\Admin\FieldComponents;

use Activelogiclabs\Administration\Admin\FieldComponent;
use Illuminate\Support\Facades\Storage;

class Image extends FieldComponent
{
    public $imageUrl;

    public function dataView()
    {
        return "<img src='/images/{$this->value}'>";
    }

    public function fieldView()
    {
        //TODO: convert to file input when layout is decided
        $src = '/images/' . $this->value;

        return view('administration::components.image', ['name' => $this->name, 'value' => $this->value, 'src' => $src]);
    }

    public function onSubmit()
    {
        return $this->imageUrl = $this->value->store($this->definition['storage_path']);
    }

    public function getUrl()
    {
        return '/images/' . $this->imageUrl;
    }

    public function onDelete()
    {
        if (Storage::delete($this->value)) {
            return null;
        }

        return $this->value;
    }
}