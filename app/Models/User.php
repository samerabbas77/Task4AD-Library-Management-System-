<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
  
    //....................JWT..............
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    //.......................JWT.............
    /**
     * one To many (user have mony borrows)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function borrows(): HasMany
    {
        return $this->hasMany(Borrow::class);
    }

    /**
     * one to many relation(user has mony ratings)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
//.......................................................................................
//.......................................................................................


      // Define the relationship between users and roles (assuming you have a roles and permissions system)

  
    //   public function permissions()
    //   {
    //       return $this->roles->flatMap(function ($role) {
    //           return $role->permissions;
    //       })->unique();
    //   }
  
    //   public function hasPermission($permission)
    //   {
    //       return $this->permissions()->contains('name', $permission);
    //   }
}
