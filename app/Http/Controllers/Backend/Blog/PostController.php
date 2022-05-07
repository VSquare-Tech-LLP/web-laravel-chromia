<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Domains\Auth\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Models\Blog\Tag;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!Gate::allows('post_access')) {
            throw UnauthorizedException::forPermissions([]);
        }
        return view('backend.blog.post.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!Gate::allows('post_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $categories = Category::get();
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Administrator', 'Super Author', 'Author']);
        })->get();
        $tags = Tag::get();

        return view('backend.blog.post.create', compact('categories', 'users', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('post_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        if ($request->action == 'Draft') {
            $validatedData = $this->validate($request, [
                'title' => 'required',
                'slug' => 'nullable',
                'body' => 'nullable',
                'main_category' => 'required',
                'categories' => 'nullable',
                'meta_title' => 'nullable',
                'meta_description' => 'nullable',
                'user_id' => 'required',
                'is_featured' => 'nullable',
            ], [
                'main_category.required' => 'The Featured Category required',
            ]);
        } else {
            $validatedData = $this->validate($request, [
                'title' => 'required',
                'slug' => 'sometimes|unique:posts',
                'body' => 'required',
                'main_category' => 'required',
                'categories' => 'required|array',
                "categories.*" => "required|distinct",
                'feature_image' => 'image|mimes:jpeg,jpg,png,gif',
                'meta_title' => 'nullable',
                'meta_description' => 'nullable',
                'user_id' => 'required',
                'is_featured' => 'nullable',
            ], [
                'main_category.required' => 'The Featured Category required',
            ]);
        }
        $validatedData['published_status'] = $request->action == 'Draft' ? 0 : 1;
        unset($validatedData['categories']);
        unset($validatedData['tags']);
        unset($validatedData['feature_image']);

        $post = Post::create($validatedData);

        if ($request->categories) {
            $post->categories()->sync($request->categories);
        }

        if ($request->tags) {
            $tags = [];
            foreach ($request->tags as $item) {
                $tag = Tag::firstOrCreate([
                    'name' => $item,
                ], [
                    'slug' => str_limit(str_slug($item), 160),
                ]);
                $tags[] = $tag->id;
            }
            $post->tags()->sync($tags);
        }

        if ($request->hasFile('feature_image')) {
            $post->addMedia($request->file('feature_image'))->toMediaCollection('featured_post_image');
        }

        return redirect()->route('admin.posts.edit', ['post' => $post])->withFlashSuccess(__('The Post is successfully created.'));
    }

    /**
     * Display the specified resource.
     * @param Post $post
     * @return Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param Post $post
     * @return Response
     */
    public function edit(Post $post)
    {
        if (!Gate::allows('post_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $categories = Category::get();
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Administrator', 'Super Author', 'Author']);
        })->get();
        $tags = Tag::get();

        return view('backend.blog.post.edit', compact('post', 'categories', 'users', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Post $post
     * @return Response
     */
    public function update(Request $request, Post $post)
    {
        if (!Gate::allows('post_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $action = $request->action;
        $replace = true;
        if ($action == 'Save Revision And Draft') {
            $action = 'Draft';
            $replace = false;
        }
        if ($request->action == 'Draft') {
            $validatedData = $this->validate($request, [
                'title' => 'required',
                'slug' => 'nullable',
                'body' => 'nullable',
                'main_category' => 'required',
                'categories' => 'nullable',
                'meta_title' => 'nullable',
                'meta_description' => 'nullable',
                'user_id' => 'required',
                'is_featured' => 'nullable',
            ], [
                'main_category.required' => 'The Featured Category required',
            ]);
        } else {
            $validatedData = $this->validate($request, [
                'title' => 'required',
                'slug' => 'sometimes',
                'body' => 'required',
                'main_category' => 'required',
                'categories' => 'required|array',
                "categories.*" => "required|distinct",
                'feature_image' => 'image|mimes:jpeg,jpg,png,gif',
                'meta_title' => 'nullable',
                'meta_description' => 'nullable',
                'user_id' => 'required',
                'is_featured' => 'nullable',
            ], [
                'main_category.required' => 'The Featured Category required',
            ]);
        }

        unset($validatedData['categories']);
        unset($validatedData['tags']);
        unset($validatedData['feature_image']);

        $validatedData['published_status'] = $request->action == 'Draft' ? 0 : 1;
        if ($validatedData['published_status'] == 1) {
            if ($post->published_status == '0') {
                $validatedDatap['created_at'] = Carbon::now();
            }
        }

        $oldData = ['title' => $post->title, 'body' => $post->body];

        $post->update($validatedData);

        if ($request->categories) {
            $post->categories()->sync($request->categories);
        }

        if ($request->tags) {
            $tags = [];
            foreach ($request->tags as $item) {
                $tag = Tag::firstOrCreate([
                    'name' => $item,
                ], [
                    'slug' => str_limit(str_slug($item), 160),
                ]);
                $tags[] = $tag->id;
            }
            $post->tags()->sync($tags);
        }

        if ($request->hasFile('feature_image')) {
            $post->addMedia($request->file('feature_image'))->toMediaCollection('featured_post_image');
        }

        //Creating Revision for post
        $this->createRevision($post, $oldData);

        return redirect()->route('admin.posts.edit', ['post' => $post])->withFlashSuccess(__('The Post is successfully udpated.'));
    }

    /**
     * create revisions
     * @param Post $post
     * @param array $old_data
     **/
    protected function createRevision($post, $old_data)
    {
        $post_data = Post::where('title', '=', $old_data['title'])->Where('body', '=', $old_data['body'])->first();
        // here true means post data updated
        $is_post_updated = $post_data ? false : true;

        //=====If post is published and has no revisions new revision is created and if revision exist and post updated then new revision added======//
        if (($post->published_status == 1 && count($post->revisions) == 0)
            || (count($post->revisions) > 0 && $is_post_updated == true)) {
            $this->replicated($post);
        }
    }

    /**
     * replicate post(duplicate create)
     * @param Post $post
     **/
    protected function replicated(Post $post)
    {
        $replicatePost = $post->replicate();
        $replicatePost->parent_id = $post->id;
        $replicatePost->slug = time() . '-' . $replicatePost->slug;
        $replicatePost->is_revision = 1;
        $replicatePost->save();
    }

    /**
     * Remove the specified resource from storage.
     * @param Post $post
     * @return Response
     */
    public function destroy(Post $post)
    {
        if (!Gate::allows('post_delete')) {
            throw UnauthorizedException::forPermissions([]);
        }

        if ($post->categories()->count()) {
            $post->categories()->detach();
        }
        if ($post->tags()->count()) {
            $post->tags()->detach();
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->withFlashSuccess(__('The Post is successfully deleted.'));
    }

    public function postPreview($slug)
    {
        $post = Post::withoutGlobalScope('published')->where('slug', '=', $slug)->first();
        $next_post = Post::where('id', '>', $post->id)->orderBy('id', 'asc')->first();
        $prev_post = Post::where('id', '<', $post->id)->orderBy('id', 'asc')->first();

        $main_category = $post->mainCategory->id;
        $related_category_ids = $post->categories->pluck('categories.id');
        $related_category_ids->prepend($main_category);
        $post_related_posts = Post::whereHas('categories', function ($q) use ($related_category_ids) {
            $q->whereIn('category_id', $related_category_ids);
        })->where('id', '<>', $post->id)
            ->take(25)->with(['keyword', 'keyword.template'])
            ->get();
        $post->post_related_posts = $post_related_posts;
        return view('frontend.single-post', compact('post', 'next_post', 'prev_post', 'post_related_posts'));
    }
}
