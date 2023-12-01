<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Author;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\Author\AuthorResource;
use App\Http\Resources\Author\AuthorCollection;

class AuthorController extends ResponseController
{
    public function index(Request $request)
    {
        $query = Author::latest('id');
        $name = $request->name;
        $email = $request->email;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = Author::count();
        if($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if($email) {
            $query->where('email$email', 'like', '%' . $email . '%');
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $authors = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'authors' => new AuthorCollection($authors),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    public function store(Request $request) {
        $name = $request->name;
        $email = $request->email;
        $birth_date = Carbon::parse($request->birth_date);
        $death_date = Carbon::parse($request->death_date);
        $nationality = $request->nationality;
        $biblography = $request->biblography;

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'email' => 'required|max:50',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }

        try {
            $author = Author::create([
                'name' => $name,
                'email' => $email,
                'birth_date' => $birth_date,
                'death_date' => $death_date,
                'nationality' => $nationality,
                'biblography' => $biblography,
            ]);
            
            $data = [
                'author' => new AuthorResource($author),
                'message' => "Author created Successfully"
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

    public function show($id) {
        $author = Author::find($id);
        if($author) {
            $data = [
                'author' => new AuthorResource($author),
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Author not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function update(Request $request, $id) {
        $name = $request->name;
        $email = $request->email;
        $birth_date = Carbon::parse($request->birth_date);
        $death_date = Carbon::parse($request->death_date);
        $nationality = $request->nationality;
        $biblography = $request->biblography;
        $author = Author::find($id);

        if($author) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:50',
                'email' => 'required|max:50',
            ]);
    
            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }
    
            try {
                $author->update([
                    'name' => $name,
                    'email' => $email,
                    'birth_date' => $birth_date,
                    'death_date' => $death_date,
                    'nationality' => $nationality,
                    'biblography' => $biblography,
                ]);
                
                $data = [
                    'author' => new AuthorResource($author),
                    'message' => "Author Updated Successfully"
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
                'message' => 'Author not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function delete($id) {
        $author = Author::find($id);
        if($author) {
            $author->delete();
            Helper::deleteImage($author->image, '/');
            $data = [
                'author' => [],
                'message' => "Author Deleted Successfully"
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Author not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
