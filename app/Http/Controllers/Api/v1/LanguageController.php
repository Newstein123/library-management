<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\Language\LanguageResource;
use App\Http\Resources\Language\LanguageCollection;

class LanguageController extends ResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Language::latest('id');
        $aisle = $request->aisle;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = Language::count();
        if($aisle) {
            $query->where('aisle', 'like', '%' . $aisle . '%');
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $languages = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'languages' => new LanguageCollection($languages),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $value = $request->value;
        

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:languages',
            'value' => 'required|max:50|unique:languages',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }

        try {
            $language = Language::create([
                'name' => $name,
                'value' => $value,
            ]);
            
            $data = [
                'language' => new LanguageResource($language),
                'message' => "Language created Successfully"
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $language = Language::find($id);
        if($language) {
            $data = [
                'language' => new LanguageResource($language),
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Language not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $name = $request->name;
        $value = $request->value;
        
        $language = Language::find($id);

        if($language) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50|unique:languages,name,' . $id,
                'value' => 'required|max:50|unique:languages,value,' . $id,
            ]);            
    
            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }
    
            try {
                $language->update([
                    'name' => $name,
                    'value' => $value,
                ]);
                
                $data = [
                    'language' => new LanguageResource($language),
                    'message' => "Language Updated Successfully"
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
                'message' => 'Language not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $language = Language::find($id);
        if($language) {
            $data = [
                'language' => [],
                'message' => "Language Deleted Successfully"
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Language not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
