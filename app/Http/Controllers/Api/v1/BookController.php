<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Book\BookResource;
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
