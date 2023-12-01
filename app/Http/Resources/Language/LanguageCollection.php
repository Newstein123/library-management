<?php

namespace App\Http\Resources\Language;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LanguageCollection extends ResourceCollection
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
                'value' => $item->value,
                'created_at' => $item->created_at->toFormattedDateString(),
            ];
        })->toArray();
    }
}
