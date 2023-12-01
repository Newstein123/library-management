<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Book;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Book\BookResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Book\BookCollection;
use App\Http\Controllers\ResponseController;

class BookController extends ResponseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::latest('id');

        $name = $request->name;
        $category_id = $request->category_id;
        $author_id = $request->author_id;
        $registered_no = $request->registered_no;
        $lang_id = $request->lang_id;
        $location_id = $request->location_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;

        $total = Book::count();
        
        if($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }

        if($category_id) {
            $query->whereHas('category', function($q) use($category_id) {
                $q->where('id', $category_id);
            });
        }

        if($author_id) {
            $query->whereHas('author', function($q) use($author_id) {
                $q->where('id', $author_id);
            });
        }
        if($lang_id) {
            $query->whereHas('language', function($q) use($lang_id) {
                $q->where('id', $lang_id);
            });
        }
        if($location_id) {
            $query->whereHas('location', function($q) use($location_id) {
                $q->where('id', $location_id);
            });
        }

        if($registered_no) {
            $query->where('registered_no', $registered_no);
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $books = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'books' => new BookCollection($books),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'category_id' => 'required|integer',
            'author_id' => 'required|integer',
            'location_id' => 'required|integer',
            'language_id' => 'required|integer',
            'publisher_id' => 'required|integer',
            'quantity' => 'required|integer',
            'pages' => 'required|integer',
            'published_year' => 'required',
            'fee_per_day' => 'required|integer',
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
            $filename = Helper::storeImage($request->image, '/image/book');
        }

        try {
            
            $book = Book::create([
                'code' => $request->code,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'author_id' => $request->author_id,
                'quantity' => $request->quantity,
                'pages' => $request->pages,
                'language_id' => $request->language_id,
                'publisher_id' => $request->publisher_id,
                'location_id' => $request->location_id,
                'language_id' => $request->language_id,
                'published_year' => Carbon::parse($request->published_year),
                'description' => $request->description,
                'fee_per_day' => $request->fee_per_day,
                'image' => $filename ?? ''
            ]);
            
            $data = [
                'book$book' => new BookResource($book),
                'message' => "Book created Successfully"
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
        $books = Book::find($id);
        if($books) {
            $data = [
                'books' => new BookResource($books),
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $book = Book::find($id);

        if($book) {
            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:50',
                'name' => 'required|string|max:50',
                'category_id' => 'required|integer',
                'author_id' => 'required|integer',
                'location_id' => 'required|integer',
                'language_id' => 'required|integer',
                'publisher_id' => 'required|integer',
                'quantity' => 'required|integer',
                'pages' => 'required|integer',
                'published_year' => 'required',
                'fee_per_day' => 'required|integer',
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
                Helper::deleteImage($book->image, '/image/book');
                $filename = Helper::storeImage($request->image, '/image/book');
            }
    
            try {
                $book->update([
                    'code' => $request->code,
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'author_id' => $request->author_id,
                    'quantity' => $request->quantity,
                    'pages' => $request->pages,
                    'language_id' => $request->language_id,
                    'publisher_id' => $request->publisher_id,
                    'location_id' => $request->location_id,
                    'language_id' => $request->language_id,
                    'published_year' => Carbon::parse($request->published_year),
                    'description' => $request->description,
                    'fee_per_day' => $request->fee_per_day,
                    'image' => $filename ?? $book->image,
                ]);
                
                $data = [
                    'book' => new BookResource($book),
                    'message' => "Book Updated Successfully"
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if($book) {
            $book->delete();
            Helper::deleteImage($book->image, '/');
            $data = [
                'book' => [],
                'message' => "Bbook Deleted Successfully"
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Book not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
