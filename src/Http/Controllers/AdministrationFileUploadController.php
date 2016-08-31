<?php

namespace Activelogiclabs\Administration\Http\Controllers;

use App\Http\Controllers\Controller;

class AdministrationFileUploadController extends Controller
{
    public function handleFileUpload() {
        return ["success" => time()];
    }
}