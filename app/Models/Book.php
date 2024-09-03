<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title','author','description','published_at','category_id'];


    /**
     * oneToMany (book has many borrows)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * one to many relation(book belongs to one cayegory)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * one to many relation(Book has mony ratings)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
