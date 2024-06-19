<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'path', 'url', 'pack_id'];

    // protected $appends = [
    //     'thumbnail',
    // ];
    public function pack()
    {
        return $this->belongsTo(Pack::class);
    }

    public function getThumbnailAttribute()
    {
        return str_replace('template_images', 'template_images/thumbnails', $this->url);
    }
}
