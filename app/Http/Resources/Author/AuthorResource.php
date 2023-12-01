<?php

namespace App\Http\Resources\Author;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
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
            'birth_date' => Carbon::parse($this->birth_date)->toFormattedDateString(),
            'death_date' => Carbon::parse($this->death_date)->toFormattedDateString(),
            'email' => $this->email,
            'nationality' => $this->nationality,
            'biblography' => $this->biblography,
            'created_at' => $this->created_at->toFormattedDateString(),
        ];
    }
}
