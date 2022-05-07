<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Tag;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Exceptions\UnauthorizedException;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('tag_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.blog.tag.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('tag_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.blog.tag.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('tag_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => ['sometimes', 'unique:categories'],
            'image' => ['nullable', 'mimes:jpg,bmp,png'],
            'description' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ]);

        unset($validatedData['image']);
        $category = Tag::create($validatedData);
        if ($request->has('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('tag_image');
        }

        return redirect()->route('admin.tags.index')->withFlashSuccess(__('Tag is successfully created.'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        if (!Gate::allows('tag_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.blog.tag.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        if (!Gate::allows('tag_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'slug' => ['sometimes', Rule::unique('tags')->ignore($tag->id)],
            'image' => ['nullable', 'mimes:jpg,bmp,png'],
            'description' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ]);

        unset($validatedData['image']);
        $tag->update($validatedData);
        if ($request->has('image')) {
            $tag->addMedia($request->file('image'))->toMediaCollection('tag_image');
        }

        return redirect()->route('admin.tags.index')->withFlashSuccess(__('Tag is successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if (!Gate::allows('tag_delete')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $tag->delete();

        return redirect()->route('admin.tags.index')->withFlashSuccess(__('Tag is successfully deleted.'));
    }
}
