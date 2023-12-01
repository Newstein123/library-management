<?php

namespace App\Http\Resources\Transaction;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function($item) {
            return [
                'book' => $item->book->name,
                'user' => $item->user->name,
                'issue_date' => Carbon::parse($item->issue_date)->toFormattedDateString(),
                'return_date' => $item->return_date != null ?  Carbon::parse($item->return_date)->toFormattedDateString() : null,
                'expected_return_date' => Carbon::parse($item->expected_return_date)->toFormattedDateString(),
                'fee_per_day' => $item->book->fee_per_day,
                'late_fee' => $item->late_fee,
                'total_fee' => $item->total_fee,
                'remarks' => $item->remarks,
            ];
        })->toArray();
    }
}
