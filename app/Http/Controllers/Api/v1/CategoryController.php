<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Helpers\Helper;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategoryCollection;

class CategoryController extends ResponseController
{
    public function index(Request $request) {
        $query = Category::latest('id');
        $name = $request->name;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = Category::count();
        if($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $category = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'categories' => new CategoryCollection($category),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }  
    
    public function show($id) {
        $category = Category::find($id);
        if($category) {
            $data = [
                'category' => new CategoryResource($category),
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Category not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function store(Request $request) {
        $name = $request->name;
        $value = $request->value;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'value' => 'required|max:50|unique:categories',
            'image' => 'image|mimes:jpeg,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }
        
        if($request->hasFile('image')) {
            $filename = Helper::storeImage($request->image, '/image/category');
        }

        try {
            
            $category = Category::create([
                'name' => $name,
                'value' => $value,
                'image' => $filename ?? '',
            ]);
            
            $data = [
                'category' => new CategoryResource($category),
                'message' => "Category created Successfully"
            ];
            return $this->successResponse($data);
        } catch (\Exception $e) {
            $errors =  [
                'code' => 'E0001',
                'message' => 'An error occurred.',
                'details' => $e->getMessage(),
            ];
            return $this->failResponse(null, $errors);
        }

    }

    public function update(Request $request, $id) {
        $name = $request->name;
        $value = $request->value;
        $category = Category::find($id);

        if($category) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'value' => 'required|max:50|unique:categories,value,'. $id,
                'image' => 'image|mimes:jpeg,jpg|max:2048'
            ]);
    
            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }
    
            if($request->hasFile('image')) {
                Helper::deleteImage($category->image, '/image/category');
                $filename = Helper::storeImage($request->image, '/image/category');
            }
    
            try {
                $category->update([
                    'name' => $name,
                    'value' => $value,
                    'image' => $filename ?? $request->image,
                ]);
                
                $data = [
                    'category' => new CategoryResource($category),
                    'message' => "Category Updated Successfully"
                ];
                return $this->successResponse($data);
            } catch (\Exception $e) {
                $errors =  [
                    'code' => 'E0001',
                    'message' => 'An error occurred.',
                    'details' => $e->getMessage(),
                ];
                return $this->failResponse(null, $errors);
            }
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Category not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function delete($id) {
        $category = Category::find($id);
        if($category) {
            $category->delete();
            Helper::deleteImage($category->image, '/');
            $data = [
                'category' => [],
                'message' => "Category Deleted Successfully"
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Category not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
