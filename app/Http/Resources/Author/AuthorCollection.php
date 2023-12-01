<?php

namespace App\Http\Resources\Author;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorCollection extends ResourceCollection
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
                'name' => $item->name,
                'birth_date' => Carbon::parse($item->birth_date)->toFormattedDateString(),
                'email' => $item->email,
                'created_at' => $item->created_at->toFormattedDateString(),
            ];
        })->toArray();
    }
}
