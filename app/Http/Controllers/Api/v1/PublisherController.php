<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\Publisher\PublisherResource;
use App\Http\Resources\Publisher\PublisherCollection;

class PublisherController extends ResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publisher::latest('id');
        $name = $request->name;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = Publisher::count();
        if($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $publishers = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'publishers' => new PublisherCollection($publishers),
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
        $address = $request->address;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'address' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }

        try {
            $publisher = Publisher::create([
                'name' => $name,
                'address' => $address,
            ]);
            
            $data = [
                'publisher' => new PublisherResource($publisher),
                'message' => "Publisher created Successfully"
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
        $publisher = Publisher::find($id);
        if($publisher) {
            $data = [
                'publisher' => new PublisherResource($publisher),
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Publisher not found',
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
        $address = $request->address;
        
        $publisher = Publisher::find($id);

        if($publisher) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
            ]);            
    
            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }
    
            try {
                $publisher->update([
                    'name' => $name,
                    'address' => $address,
                ]);
                
                $data = [
                    'publisher' => new PublisherResource($publisher),
                    'message' => "Publisher Updated Successfully"
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
                'message' => 'Publisher not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $publisher = Publisher::find($id);
        if($publisher) {
            $data = [
                'publisher' => [],
                'message' => "Publisher Deleted Successfully"
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Publisher not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
