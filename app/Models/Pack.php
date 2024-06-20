<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'category_id', 'fetured_image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function getFeturedImageThumbnailAttribute()
    {
        $this->photos->append('thumbnail');
        return str_replace('template_images', 'template_images/thumbnails', $this->fetured_image);
    }
}
