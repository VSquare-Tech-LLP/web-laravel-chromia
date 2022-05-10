<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Auth\Models\User;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class HomeController.
 */
class HomeController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $latest_posts = Post::latest()->paginate(6)->withPath('\latest-posts');
        return view('frontend.index', compact('latest_posts'));
    }

    public function singlePost($slug)
    {
        $post = Post::where('slug', '=', $slug)->first();
        if ($post) {
            $next_post = Post::where('id', '>', $post->id)->orderBy('id', 'asc')->first();
            $prev_post = Post::where('id', '<', $post->id)->orderBy('id', 'asc')->first();

            $main_category = $post->mainCategory->id;
            $related_category_ids = $post->categories->pluck('categories.id');
            $related_category_ids->prepend($main_category);
            $post_related_posts = Post::whereHas('categories', function ($q) use ($related_category_ids) {
                                    $q->whereIn('category_id', $related_category_ids);
                                })->where('id', '<>', $post->id)
                                ->take(25)
                                ->get();
            $post->post_related_posts = $post_related_posts;
            return view('frontend.single-post', compact('post', 'next_post', 'prev_post', 'post_related_posts'));
        } else {
            return $this->singlePage($slug);
        }
    }

    public function singlePage($slug)
    {
        $page = Post::withoutGlobalScope('post')->where('slug', '=', $slug)->page()->first();
        if ($page) {
            return view('frontend.single-page', compact('page'));
        }
        abort(404);
    }

    public function singleAuthor($username)
    {
        $user = User::where('username', '=', $username)->first();
        if ($user) {
            $user_posts = $user->posts()->latest()->paginate(6);
            return view('frontend.single-author', compact('user', 'user_posts'));
        }
        abort(404);
    }

    public function singleCategory($slug)
    {
        $the_category = Category::with(['parentRecursive', 'childrenRecursive'])->where('slug', '=', $slug)->first();
        if ($the_category) {
            if (file_exists(storage_path('app/categories/all.json'))) {
                $categories = collect(json_decode(Storage::get('categories/all.json')));
            } else {
                $categories = Category::with('childrenRecursive')->get();
            }
            $category_posts = $the_category->posts()->latest()->paginate(6);

            return view('frontend.single-category', compact('the_category', 'categories', 'category_posts'));
        }
        abort(404);
    }

    public function categories()
    {
        $categories = Category::with(['children','parent'])->get();
        $topCategories = $categories->whereNull('parent_id');
        return view('frontend.categories',compact('categories','topCategories'));
    }

    public function search(Request $request)
    {
        $search_term = $request->get('q');
        if ($search_term) {
            $posts = Post::where('title', 'like', '%'.$search_term.'%')
                ->paginate(6)->withQueryString();
            $categories = Category::where('name', 'like', '%'.$search_term.'%')
                ->paginate(6)->withQueryString();

            return view('frontend.search-page', compact('search_term', 'categories', 'posts'));
        }
        abort(404);
    }
}
