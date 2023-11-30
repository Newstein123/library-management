<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'phone' => $item->phone,
                'registered_no' => $item->registered_no,
                'status' => $item->is_active == 1 ? 'active' : 'inactive',
                'created_at' => $item->created_at->toFormattedDateString(),
           ];
        })->toArray();
    }
}
