<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\BookLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\Location\LocationResource;
use App\Http\Resources\Location\LocationCollection;

class LocationController extends ResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BookLocation::latest('id');
        $aisle = $request->aisle;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = BookLocation::count();
        if($aisle) {
            $query->where('aisle', 'like', '%' . $aisle . '%');
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $locations = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'locations' => new LocationCollection($locations),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $aisle = $request->aisle;

        $validator = Validator::make($request->all(), [
            'aisle' => 'required|max:50|unique:book_locations',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }

        try {
            $location = BookLocation::create([
                'aisle' => $aisle,
            ]);
            
            $data = [
                'location' => new LocationResource($location),
                'message' => "Location created Successfully"
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
        $location = BookLocation::find($id);
        if($location) {
            $data = [
                'location' => new LocationResource($location),
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Location not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $aisle = $request->aisle;
        
        $location = BookLocation::find($id);

        if($location) {
            $validator = Validator::make($request->all(), [
                'aisle' => 'required|max:10|unique:book_locations,aisle,' . $id,
            ]);            
    
            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }
    
            try {
                $location->update([
                    'aisle' => $aisle,
                ]);
                
                $data = [
                    'location' => new LocationResource($location),
                    'message' => "Location Updated Successfully"
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
                'message' => 'location not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $location = BookLocation::find($id);
        if($location) {
            $data = [
                'location' => [],
                'message' => "Location Deleted Successfully"
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Location not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
