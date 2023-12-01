<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function($item) {
            return  [
                 'code' => $item->code,
                 'name' => $item->name,
                 'category' => $item->category->name,
                 'author' => $item->author->name,
                 'quantity' => $item->quantity,
                //  'status' => $item->is_active == 1 ? 'active' : 'inactive',
                 'created_at' => $item->created_at->toFormattedDateString(),
            ];
         })->toArray();
    }
}
