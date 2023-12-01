<?php

namespace App\Http\Resources\Book;

use Carbon\Carbon;
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
            'name' => $this->name,
            'category' => $this->category->name,
            'author' => $this->author->name,
            'quantity' => $this->quantity,
            'pages' => $this->pages,
            'language' => $this->language->name,
            'publisher' => $this->publisher->name,
            'location' => $this->location->aisle,
            'language' => $this->language->name,
            'published_year' => Carbon::parse($this->published_year)->year,
            'description' => $this->description,
        //  'status' => $this->is_active == 1 ? 'active' : 'inactive',
            'created_at' => $this->created_at->toFormattedDateString(),
        ];
    }
}
