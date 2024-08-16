<?php

namespace App\Domains\Flux\Models;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $guarded = [];
    protected $appends = ['thumb_url'];
    public function getThumbUrlAttribute()
    {
        return str_replace('source_images', 'source_thumbs', $this->url);
    }

}
