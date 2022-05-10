<?php

namespace App\Models\Blog;

use App\Domains\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravelista\Comments\Commentable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Post extends Model implements HasMedia
{
    use Commentable, InteractsWithMedia, HasFactory, SoftDeletes, HasSlug;

    protected $dates = ['published_at', 'display_published_at'];

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'main_category',
        'user_id',
        'parent_id',
        'slug',
        'title',
        'image',
        'body',
        'meta_title',
        'meta_description',
        'is_featured',
        'excerpt',
        'published_status',
        'type',
        'extras',
        'is_revision',
        'display_published_at',
        'published_at',
    ];

    /**
     * The "booted" method of the model.
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('published_status', '=', '1');
        });

        // post scope globally
        static::addGlobalScope('post', function (Builder $builder) {
            $builder->where('type', '=', 1)->orWhere('type', '=', 0);
        });

        //Getting none-revision posts
        static::addGlobalScope('none-revision', function (Builder $builder) {
            $builder->where('is_revision', '=', 0);
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * regiter media for user
     **/
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('featured_post_image')
            ->singleFile();
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(385)
            ->height(220)
            ->sharpen(10)
            ->performOnCollections('featured_post_image');

        $this->addMediaConversion('large')
            ->width(700)
            ->height(400)
            ->sharpen(10)
            ->performOnCollections('featured_post_image');

        $this->addMediaConversion('medium')
            ->width(530)
            ->height(300)
            ->sharpen(10)
            ->performOnCollections('featured_post_image');
    }

    /* This model relationship method start */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(Category::class, 'main_category');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function revisions()
    {
        return $this->hasMany(static::class, 'parent_id')->withoutGlobalScope('none-revision');
    }

    public function getOriginalAttribute()
    {
        $media = $this->getMedia('featured_post_image');

        return $media[0]->getUrl();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    /*  This model relationship method end */


    /*  This model attributes method start */

    public function getLargeAttribute()
    {
        $media = $this->getMedia('featured_post_image');

        return $media[0]->getUrl('large');
    }

    public function getMediumAttribute()
    {
        $media = $this->getMedia('featured_post_image');

        return $media[0]->getUrl('medium');
    }

    public function getThumbAttribute()
    {
        $media = $this->getMedia('featured_post_image');

        return $media[0]->getUrl('thumb');
    }

    public function getCategoryNamesAttribute()
    {
        if ($this->categories) {
            return implode(',', $this->categories()->pluck('name')->toArray());
        } else {
            return 'none';
        }
    }

    public function getPostStatusAttribute()
    {
        $status = ['0' => 'Drafted', '1' => 'Published'];

        return $status[$this->published_status];
    }

    public function getMetaAttribute()
    {
        $meta = json_decode($this->extras);
        return $meta;
    }
    /*  This model attributes method end */


    /*  This model scope method start */

    public function scopePage($query)
    {
        return $query->where('type', '=', 2);
    }
    /*  This model scope method end */
}
