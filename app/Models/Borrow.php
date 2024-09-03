<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrow extends Model
{
    use HasFactory;

    protected $fillable = ['book_id','user_id','borrowed_at','due_date', 'returned_at'];
    
    /**
     * one To Many Relation( borrow has one book)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
   
    /**
     * one To Many Relation(borrow have one user)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A boot to set the user_id with current user id
     * @return void
     */
    protected static function booted()
    {
        parent::boot();
        static::creating(function ($borrow) {
                $borrow->user_id = auth('api')->user()->id;
        });
    }
    
    // protected static function boot()
    // {
    // //    return auth('api');
    // //     parent::boot();

    // //     static::creating(function ($borrow) {
    // //         $borrow->user_id = auth('api')->user()->id;
    // //     });

    // }
    
}
