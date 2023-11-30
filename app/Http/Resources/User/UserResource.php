<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'registered_no' => $this->registered_no,
            'identification_no' => $this->identification_no,
            'status' => $this->is_active == 1 ? 'active' : 'inactive',
            'created_at' => $this->created_at->toFormattedDateString(),
        ];
    }
}
