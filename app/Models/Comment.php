<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BeyondCode\Comments\Traits\HasComments;
use Exception;

class Comment extends Model
{
    use HasFactory, HasComments;

    protected $fillable = [
        'comment',
        'commentaddable_type',
        'commentaddable_id',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean'
    ];

    protected $with = ['commentaddable:id,image,first_name,last_name'];

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function commentaddable()
    {
        return $this->morphTo();
    }

    public function commentator()
    {
        return $this->belongsTo($this->getAuthModelName(), 'user_id');
    }

    public function approve()
    {
        $this->update([
            'is_approved' => true,
        ]);

        return $this;
    }

    public function disapprove()
    {
        $this->update([
            'is_approved' => false,
        ]);

        return $this;
    }

    protected function getAuthModelName()
    {
        if (config('comments.user_model')) {
            return config('comments.user_model');
        }

        if (!is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the commentator model name.');
    }
}
