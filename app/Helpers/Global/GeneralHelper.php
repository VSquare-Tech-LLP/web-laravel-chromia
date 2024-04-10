<?php

use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Carbon\Carbon;

if (!function_exists('appName')) {
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

if (!function_exists('carbon')) {
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

if (!function_exists('homeRoute')) {
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

if (!function_exists('appOgImage')) {
    /**
     * Helper to grab the application OG image.
     *
     * @return mixed
     */
    function appOgImage()
    {
        if (config('og_image')) {
            return asset('storage/logos/' . config('og_image'));
        } else {
            return asset('images/amz-cms-og-image.png');
        }
    }
}

if (!function_exists('cdn_asset')) {
    function cdn_asset($path)
    {
        if (env('CDN_URL') == null) {
            return asset('storage/' . $path);
        } else {
            return env('CDN_URL') . '/' . $path;
        }
    }
}

if (!function_exists('app_json')) {
    function app_json($data, $status = 'success')
    {
        return response()->json(['status' => $status, 'data' => $data]);
    }
}
