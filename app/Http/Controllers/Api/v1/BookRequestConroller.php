<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Helpers\Helper;
use App\Models\BookRequest;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\Book\BookRequestCollection;
use App\Http\Resources\Book\BookRequestResource;
use App\Http\Resources\Transaction\TransactionResource;

class BookRequestConroller extends ResponseController
{   
    public function index(Request $request) {
        $query = BookRequest::latest('id');
        $book_id = $request->book_id;
        $category_id = $request->category_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = BookRequest::count();

        if($book_id) {
            $query->where('book_id', $book_id);
        }

        if($category_id) {
            $query->whereHas('book', function($q) use($category_id) {
                $q->where('category_id', $category_id);
            });
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $book_request = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'book_requests' => new BookRequestCollection($book_request),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    public function show($id) {
        $book_request = BookRequest::find($id);
        if($book_request) {
            $data = [
                'book_request' => new BookRequestResource($book_request),
            ];
            return $this->successResponse($data);
        } else {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'Book Request not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => "Validation Fails",
            ];
            $errors = $validator->errors();
            return $this->successResponse($data, $errors);
        }
        // check book quantity
        
        $book = Book::find($request->book_id);
        $user = User::find($request->user_id);
        if(!$book || !$user) {
            $data = [];
            $error = [
                'code' => 'E0004',
                'message' => 'User or Book not found',
            ];
            return $this->successResponse($data, $error);
        }

        if($book->quantity <= 0) {
            $data = [];
            $error = [
                'code' => 'E0007',
                'message' => 'Book Not Availiable',
            ];
            return $this->successResponse($data, $error);
        }

        try {
            $book = DB::transaction(function () use ($request, $book, $user) {
                $book = BookRequest::create([
                    'book_id' => $request->book_id,
                    'user_id' => $request->user_id,
                    'request_date' => now(),
                    'status' => 'pending',
                ]);
        
                $noti_data = [
                    'user_id' => Helper::getRoleId('admin'),
                    'title'   => "Book requested from User",
                    'message' => $book->name . " is requested from ". $user->name,
                ];
                Helper::makeNotification($noti_data);
                return $book;
            });
        
            $data = [
                'book' => new BookRequestResource($book),
                'message' => "Book requested Successfully",
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
        $book_request = BookRequest::find($id);
        if($book_request) {
            $validator = Validator::make($request->all(), [
                'book_id' => 'required|integer',
                'user_id' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                $data = [
                    'message' => "Validation Fails",
                ];
                $errors = $validator->errors();
                return $this->successResponse($data, $errors);
            }

            $book = Book::find($request->book_id);
            $user = User::find($request->user_id);
            if(!$book || !$user) {
                $data = [];
                $error = [
                    'code' => 'E0004',
                    'message' => 'User or Book not found',
                ];
                return $this->successResponse($data, $error);
            }

            $status = $request->status;
            if($status != "completed") {
                try {
                    $book_request = DB::transaction(function () use ($request, $book_request) {
                        $book_request->update([
                            'status'  => $request->status,
                        ]);
                        $noti_data = [
                            'user_id' => $request->user_id,
                            'title'   => "Book status updated",
                            'message' => "Your book request is in a " . $request->status. "stage",
                        ];
                        Helper::makeNotification($noti_data);
                        return $book_request;
                    });
                
                    $data = [
                        'book' => new BookRequestResource($book_request),
                        'message' => "Book request updated Successfully",
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

            // Check book quantity 
            if($book->quantity <= 0) {
                $data = [];
                $error = [
                    'code' => 'E0007',
                    'message' => 'Book Not Availiable',
                ];
                return $this->successResponse($data, $error);
            }

            // Book transaction begins
            try {
                $transaction = DB::transaction(function() use($request, $book, $book_request) {
                    $total_fee = $book->fee_per_day * 10;
                    $transaction = Transaction::create([
                        'user_id' => $request->user_id,
                        'book_id' => $request->book_id,
                        'issue_date' => now(),
                        'return_date' => null,
                        'late_fee' => 100,
                        'total_fee' => $total_fee,
                        'expected_return_date' =>now()->addDays(10),
                        // 'remarks' => $request->remarks,
                    ]);
                    
                    // Book quantity update 
                    $book->update([
                        'quantity' => $book->quantity - 1,
                    ]);
                    // notify user 
                    
                    $noti_data = [
                        'user_id' => $request->user_id,
                        'title'   => "Book status updated",
                        'message' => "Your book request is in a " . $request->status. "stage",
                    ];
                    Helper::makeNotification($noti_data);

                    // update bookrequest 
                    $book_request->update([
                        'status' => 'completed',
                    ]);

                    return $transaction;
                });

                $data = [
                    'book' => new TransactionResource($transaction),
                    'message' => "Book request updated Successfully",
                ];
            
                return $this->successResponse($data);
            } catch(\Exception $e) {
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
                'message' => 'BookRequest not found',
            ];
            return $this->successResponse($data, $error);
        }
    }

    public function get_user_bookrequest(Request $request, $id) {
        $query = BookRequest::where('user_id', $id)->latest('id');
        $book_id = $request->book_id;
        $category_id = $request->category_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = BookRequest::count();

        if($book_id) {
            $query->where('book_id', $book_id);
        }

        if($category_id) {
            $query->whereHas('book', function($q) use($category_id) {
                $q->where('category_id', $category_id);
            });
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $book_request = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'book_requests' => new BookRequestCollection($book_request),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }
}
