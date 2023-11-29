<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController;

class CategoryController extends ResponseController
{
    public function index() {
        $data = [
            'category' => Category::all(),
        ];
        return $this->successResponse($data);
    }   
}
