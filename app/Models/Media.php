<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    use HasFactory;

    protected $table = 'media';

    protected $appends = ['full_url'];

    public function getFullUrlAttribute(){
        return $this->getFullUrl();
    }
}
