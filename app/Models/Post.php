<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use \Multicaret\Acquaintances\Traits\CanBeLiked;
use BeyondCode\Comments\Traits\HasComments;

class Post extends Model implements HasMedia
{
    use HasMediaTrait, CanBeLiked, HasComments, HasFactory;

    protected $table = "posts";

    protected $fillable = [
        'postable_type', 'postable_id', 'description',
    ];

    protected $with = ['commentable', 'media', 'postable:id,image,first_name,last_name'];

    public function postable()
    {
        return $this->morphTo();
    }

    public function commentable(){
        return $this->morphMany(Comment::class, 'commentable');
    }

}
