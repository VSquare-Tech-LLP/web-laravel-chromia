<?php

namespace App\Domains\Flux\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Auth\Models\Image;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }


    public function images()
    {
        return $this->hasMany(Image::class);
    }

    
}
