<?php

namespace App\Http\Resources\Transaction;

use App\Http\Resources\Book\BookResource;
use App\Http\Resources\User\UserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'book' => $this->book->name,
            'user' => $this->user->name,
            'issue_date' => Carbon::parse($this->issue_date)->toFormattedDateString(),
            'return_date' => $this->return_date != null ?  Carbon::parse($this->return_date)->toFormattedDateString() : null,
            'expected_return_date' => Carbon::parse($this->expected_return_date)->toFormattedDateString(),
            'fee_per_day' => $this->book->fee_per_day,
            'late_fee' => $this->late_fee,
            'total_fee' => $this->total_fee,
            'remarks' => $this->remarks,
        ];
    }
}
