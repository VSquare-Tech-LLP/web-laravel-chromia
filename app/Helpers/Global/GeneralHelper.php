<?php

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Carbon\Carbon;

if (! function_exists('appName')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function appName()
    {
        return config('app.name', 'Laravel Boilerplate');
    }
}

if (! function_exists('carbon')) {
    /**
     * Create a new Carbon instance from a time.
     *
     * @param $time
     * @return Carbon
     *
     * @throws Exception
     */
    function carbon($time)
    {
        return new Carbon($time);
    }
}

if (! function_exists('homeRoute')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function homeRoute()
    {
        if (auth()->check()) {
            if (auth()->user()->isAdmin()) {
                return 'admin.dashboard';
            }

            if (auth()->user()->isUser()) {
                return 'frontend.user.dashboard';
            }
        }

        return 'frontend.index';
    }
}

if (! function_exists('appOgImage')) {
    /**
     * Helper to grab the application OG image.
     *
     * @return mixed
     */
    function appOgImage()
    {
        if(config('og_image')){
            return asset('storage/logos/'.config('og_image'));
        }else{
            return asset('images/amz-cms-og-image.png');
        }

    }
}

if (! function_exists('permalinkUrl')) {
    /**
     * @return string
     */
    function permalinkUrl($post)
    {
        switch (config('permalink_pattern')) {

            case 'day':
                return config('app.url').'/'.postBase().''.$post->created_at->format('Y').'/'.$post->created_at->format('m').'/'.$post->created_at->format('d').'/'.$post->slug;

                break;
            case 'month':
                return config('app.url').'/'.postBase().''.$post->created_at->format('Y').'/'.$post->created_at->format('m').'/'.$post->slug;

                break;
            case 'archives':
                return config('app.url').'/'.postBase().''.'archives'.'/'.$post->id;

                break;
            default:
                return config('app.url').'/'.postBase().''.$post->slug;

                break;
        }
    }
}

if (! function_exists('permalinkPrepare')) {
    /**
     * @return string
     */
    function permalinkPrepare($post)
    {
        switch (config('permalink_pattern')) {

            case 'day':
                return config('app.url').'/'.postBase().''.$post->created_at->format('Y').'/'.$post->created_at->format('m').'/'.$post->created_at->format('d').'/';

                break;
            case 'month':
                return config('app.url').'/'.postBase().''.$post->created_at->format('Y').'/'.$post->created_at->format('m').'/';

                break;
            case 'archives':
                return config('app.url').'/'.postBase().'archives'.'/'.$post->id;

                break;
            case 'slug':
                return config('app.url').'/'.postBase().'';
            default:
                return config('app.url').'/'.postBase().'';

                break;
        }
    }
}


if (! function_exists('categoryBase')) {
    /**
     * @return string
     */
    function categoryBase($url = false)
    {
        if ($url) {
            return config('app.url').'/'.(config('category_base')?:'category');
        }

        return config('category_base')?:'category';
    }
}

if (! function_exists('tagBase')) {
    /**
     * @return string
     */
    function tagBase($url = false)
    {
        if ($url) {
            return config('app.url').'/'.(config('tag_base')?:'tag');
        }

        return config('tag_base')?:'tag';
    }
}

if (! function_exists('pageBase')) {
    /**
     * @return string
     */
    function pageBase($url = false)
    {
        if ($url) {
            return config('app.url').''.(config('page_base')?'/'.config('page_base'):'');
        }

        return config('page_base')?:'';
    }
}
if (! function_exists('postBase')) {
    /**
     * @return string
     */
    function postBase()
    {
        return config('post_base')?config('post_base').'/':'';
    }
}

if (! function_exists('menuUrl')) {
    function menuUrl($menu)
    {
        switch ($menu->type) {
            case 'Category':
                $record = Category::findOrFail($menu->item_id);
                if ($record) {
                    return categoryBase(true) . '/' . $record->slug;
                } else {
                    return abort(404);
                }

                break;
            case 'Page':
                $record = Post::withoutGlobalScope('post')->where('type', 2)->where('id', $menu->item_id)->firstOrFail();
                if ($record) {
                    return pageBase(true) . '/' . $record->slug;
                } else {
                    return abort(404);
                }
            case 'Post':
                $record = Post::findOrFail($menu->item_id);
                if ($record) {
                    return permalinkUrl($record);
                } else {
                    return abort(404);
                }

            default:
                return $menu->link;
        }
    }
}
