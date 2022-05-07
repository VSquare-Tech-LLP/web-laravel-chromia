<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model implements HasMedia
{
    use InteractsWithMedia,HasFactory,SoftDeletes,HasSlug;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'image',
        'description',
        'meta_title',
        'meta_description',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * register media for user
     **/
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('category_image')
            ->singleFile();
    }

    /* This model relationship method start */

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function mainPosts()
    {
        return $this->hasMany(Post::class, 'main_category');
    }
    /* This model relationship method end */

    /*  This model scope method start */
    /**
     * @param $query
     * @param $term
     *
     * @return mixed
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($query) use ($term) {
            $query->where('name', 'like', '%'.$term.'%');
        });
    }
}
