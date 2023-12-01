<?php

namespace App\Http\Resources\Book;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookRequestCollection extends ResourceCollection
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
                'id' => $item->id,
                'user' => $item->user->name,
                'book' => $item->book->name,
                'request_date' => Carbon::parse($item->request_date)->toFormattedDateString(),
                'status' => $item->status,
            ];
        })->toArray();
    }
}
