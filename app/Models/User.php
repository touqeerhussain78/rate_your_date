<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use App\Traits\Interact;
use Multicaret\Acquaintances\Traits\CanLike;
use Laravelista\Comments\Commenter;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, CanLike, Commenter, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'image',
        'phone',
        'forgot_code',
        'city',
        'state',
        'country',
        'dob',
        'weight',
        'height',
        'gender_interest',
        'radius',
        'about',
        'device_id',
        'device_type',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getImageAttribute($value){
        return asset("/assets/upload/user/{$value}");
    }

    public function postable(){
        return $this->morphMany(Post::class, 'postable');
    }

    public function commentaddable(){
        return $this->morphMany(Comment::class, 'commentaddable');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function hobbies()
    {
        return $this->hasMany(UserHobbie::class);
    }

}
