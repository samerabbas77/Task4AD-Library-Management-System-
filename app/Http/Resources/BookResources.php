<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Title'        => $this->title,
            'Author'       => $this->author,
            'Description'  => $this->description,
            'published_at' => $this->published_at,
            'Category'     => $this->category->name
        ];
    }
}
