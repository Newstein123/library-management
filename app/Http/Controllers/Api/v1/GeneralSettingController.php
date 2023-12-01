<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\General\GeneralSettingResource;

class GeneralSettingController extends ResponseController
{
    public function index(Request $request) {
        $query = GeneralSetting::latest('id');
        $name = $request->name;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = GeneralSetting::count();

        if($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $gs = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'general-settings' => GeneralSettingResource::collection($gs),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    public function store(Request $request) {
        $name = $request->name;
        $value = $request->value;
        $type = $request->type;

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'value' => 'required|string|max:50|unique:general_settings',
            'type' => 'required|string'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }
        
        try {
            $gs = GeneralSetting::create([
                'name' => $name,
                'value' => $value,
                'type' => $type,
            ]);

            $data = [
                'general-setting' => new GeneralSettingResource($gs),
                'message' => "General Setting created Successfully"
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
        $gs = GeneralSetting::findOrFail($id);
        $value = "";
        
        $validator = Validator::make($request->all(), [
            'value' => 'required|string|max:50|unique:general_settings',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }
        try {
            if($request->hasFile('image') ) {
                $request->validate([
                    'image' => 'required|image|mimes:jpg,jpeg,png,bmp,gif,svg'
                ]);
                $file = $request->file('image');
                if($request->hasFile('image')) {
                    Helper::deleteImage($gs->value, '/');
                    $path  = Helper::storeImage($file, 'image/logo');
                }
                
                $value = $path;
            } else {
                $value = $request->value;
            }

            $gs->update([
                'value' => $value, 
            ]);

            $data = [
                'general-setting' => new GeneralSettingResource($gs),
                'message' => "General Setting updated Successfully"
            ];
            return $this->successResponse($data);
    
            return to_route('admin.general');

        } catch(\Exception $e) {
            $errors =  [
                'code' => 'E0001',
                'message' => 'An error occurred.',
                'details' => $e->getMessage(),
            ];
            return $this->failResponse(null, $errors);
        }
    }
}
