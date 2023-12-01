<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Book;
use App\Helpers\Helper;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ResponseController;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\Transaction\TransactionCollection;

class TransactionController extends ResponseController
{
    public function index(Request $request) {
        $query = Transaction::latest('id');
        $book_id = $request->book_id;
        $user_id = $request->user_id;
        $category_id = $request->category_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = Transaction::count();

        if($book_id) {
            $query->where('book_id', $book_id);
        }

        if($user_id) {
            $query->where('user_id', $user_id);
        }

        if($category_id) {
            $query->whereHas('book', function($q) use($category_id) {
                $q->where('category_id', $category_id);
            });
        }

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [Carbon::parse($start_date), Carbon::parse($end_date)]);
        }

        $transactions = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'transactions' => new TransactionCollection($transactions),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    public function show($id) {
        $transactions = Transaction::find($id);
        if($transactions) {
            $data = [
                'transactions' => new TransactionResource($transactions),
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

    public function get_user_transactions(Request $request, $id) {
        $query = Transaction::where('user_id', $id)->latest('id');
        $book_id = $request->book_id;
        $category_id = $request->category_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $page = $request->page ?? 1;
        $perpage = $request->perpage ?? 10;
        $total = Transaction::count();

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

        $transactions = $query->skip(($page - 1) * $perpage)->take($perpage)->get();
        $data = [
            'transactions' => new TransactionCollection($transactions),
            'total' => $total,
        ];
        return $this->successResponse($data);
    }

    public function return_book(Request $request) {
        $user_id = $request->user_id;
        $book_id = $request->book_id;

        $transaction = Transaction::where('user_id', $user_id)->where('book_id', $book_id)->first();

         
        if($transaction) {
            // check for late fee
            $return_date = Carbon::parse($request->return_date);
            $expected_return_date = Carbon::parse($transaction->expected_return_date);

            $daysDifference = $return_date->diffInDays($expected_return_date);
            $isLate = false;
            if ($return_date > $expected_return_date) {
                $isLate = true;
                $total_fee = $transaction->total_fee + ($daysDifference * $transaction->late_fee);
            } 

            try {
                $transaction = DB::transaction(function () use($request, $transaction, $isLate, $total_fee) {
                    $book = Book::find($request->book_id);
                    $transaction->update([
                        'return_date' => Carbon::parse($request->return_date),
                        'total_fee' => $isLate ? $total_fee : $transaction->total_fee,
                    ]);

                    // update book quantity 
                    
                    $book->update([
                        'quantity' => $book->quantity + 1,
                    ]);

                    // notify user 
                    
                    $noti_data = [
                        'user_id' => Helper::getRoleId('admin'),
                        'title'   => "Book return from user",
                        'message' => "Book name is returned from user name",
                    ];
                    Helper::makeNotification($noti_data);
                    return $transaction;
                });

                $data = [
                    'book' => new TransactionResource($transaction),
                    'message' => "Book returned successfully",
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
                'message' => 'Transaction not found',
            ];
            return $this->successResponse($data, $error);
        }
    }
}
