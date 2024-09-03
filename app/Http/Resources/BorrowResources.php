<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Book Title'          =>$this->book->title,
            'User Name'           =>$this->user->name,
            'Borrowed Date'       =>$this->borrowed_at,
            'Due Date'            =>$this->due_date,
            'Returned Date'       =>$this->returned_at
        ];
    }
}
