<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['rate','user_id','book_id'];

    /**
     * one to many relation(Rate belongs to one user)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * one to many relation(Rate belongs to one book)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * auto fill user_id with current user id
     * @return void
     */
    protected static function booted()
    {
        parent::boot();
        static::creating(function ($rate) {
                $rate->user_id = auth('api')->user()->id;
        });
    }
}
