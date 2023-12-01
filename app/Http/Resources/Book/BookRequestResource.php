<?php

namespace App\Http\Resources\Book;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name,
            'book' => $this->book->name,
            'request_date' => Carbon::parse($this->request_date)->toFormattedDateString(),
            'status' => $this->status,
        ];
    }
}
