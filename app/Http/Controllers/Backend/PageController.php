<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\Permission\Exceptions\UnauthorizedException;
use stdClass;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (!Gate::allows('tag_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.page.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        if (!Gate::allows('tag_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.page.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('tag_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $this->validate($request, [
            'title' => 'required',
            'slug' => 'nullable',
            'body' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ]);
        $meta = new stdClass();
        if ($request->get('index_status') == 'on') {
            $meta->index_status = 1;
        } else {
            $meta->index_status = 0;
        }
        $validatedData['extras'] = json_encode($meta);
        $validatedData['published_status'] = $request->action == 'Draft' ? 0 : 1;
        $validatedData['type'] = 2;
        $validatedData['user_id'] = auth()->user()->id;
        $page = Post::create($validatedData);

        if ($request->hasFile('feature_image')) {
            $page->addMedia($request->file('feature_image'))->toMediaCollection('featured_post_image');
        }

        return redirect()->route('admin.pages.index')->withFlashSuccess(__('The Page is successfully created.'));
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
     * @param Post $page
     * @return Response
     */
    public function edit(Post $page)
    {
        if (!Gate::allows('tag_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Post $page
     * @return Response
     */
    public function update(Request $request, Post $page)
    {
        if (!Gate::allows('tag_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $this->validate($request, [
            'title' => 'required',
            'slug' => 'nullable',
            'body' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ]);
        $meta = $page->meta ?? new stdClass();
        if ($request->get('index_status') == 'on') {
            $meta->index_status = 1;
        } else {
            $meta->index_status = 0;
        }
        $validatedData['extras'] = json_encode($meta);
        $validatedData['published_status'] = $request->action == 'Draft' ? 0 : 1;
        $page->update($validatedData);

        if ($request->hasFile('feature_image')) {
            $page->addMedia($request->file('feature_image'))->toMediaCollection('featured_post_image');
        }
        return redirect()->route('admin.pages.index')->withFlashSuccess(__('The Page is successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param Post $page
     * @return Response
     */
    public function destroy(Post $page)
    {
        if (!Gate::allows('tag_delete')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $page->delete();
        return redirect()->route('admin.pages.index')->withFlashSuccess(__('The Page is successfully deleted.'));
    }

    public function pagePreview($slug)
    {
        $page = Post::withoutGlobalScope('published')->withoutGlobalScope('post')->where('slug', '=', $slug)->first();

        return view('frontend.single-page', compact('page'));
    }
}
