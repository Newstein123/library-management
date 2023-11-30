<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'category' => $this->category->name,
            'author' => $this->author->name,
            'quantity' => $this->quantity,
            'pages' => $this->pages,
            'language' => $this->language->name,
            'publisher' => $this->publisher->name,
            'location' => $this->location->name,
            'language' => $this->language->aisle,
            'published_year' => $this->published_year,
            'description' => $this->description,
        //  'status' => $this->is_active == 1 ? 'active' : 'inactive',
            'created_at' => $this->created_at->toFormattedDateString(),
        ];
    }
}
