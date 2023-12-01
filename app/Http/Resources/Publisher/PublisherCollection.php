<?php

namespace App\Http\Resources\Publisher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PublisherCollection extends ResourceCollection
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
                'address' => $item->address,
                'created_at' => $item->created_at->toFormattedDateString(),
            ];
        })->toArray();
    }
}
