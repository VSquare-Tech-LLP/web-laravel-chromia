<?php

namespace App\Providers;

use App\Models\Blog\Post;
use App\Models\Option;
use Efectn\Menu\Models\MenuItems;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

/**
 * Class AppServiceProvider.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        if (Schema::hasTable('options')) {
            foreach (Option::all() as $setting) {
                Config::set($setting->name, $setting->value);
            }
        }

        view()->composer(['frontend.includes.nav', 'frontend.includes.footer'], function ($view) {
            $menus = MenuItems::orderBy('sort')->get();
            $max_depth = $menus->max('depth');
            $footer_recent_posts = Post::latest()->take(5)->get();
            $view->with(compact('max_depth', 'menus', 'footer_recent_posts'));
        });
    }
}
