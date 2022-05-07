<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Category;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('category_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.blog.category.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('category_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $parents = Category::get();

        return view('backend.blog.category.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('category_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable',
            'slug' => ['sometimes', 'unique:categories'],
            'image' => ['nullable', 'mimes:jpg,bmp,png'],
            'description' => 'nullable',
            'metatitle' => 'nullable',
            'metadescription' => 'nullable',
        ]);

        unset($validatedData['image']);
        $category = Category::create($validatedData);
        if ($request->has('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('category_image');
        }

        return redirect()->route('admin.categories.index')->withFlashSuccess(__('The Category is successfully created.'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Models\Blog\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        if (!Gate::allows('category_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $parents = Category::whereNotIn('id', [$category->id])->get();

        return view('backend.blog.category.edit', compact('parents', 'category'));
    }

    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Blog\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        if (!Gate::allows('category_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable',
            'slug' => ['sometimes', Rule::unique('categories')->ignore($category->id)],
            'image' => ['nullable', 'mimes:jpg,bmp,png'],
            'description' => 'nullable',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ]);

        unset($validatedData['image']);
        $category->update($validatedData);
        if ($request->has('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('category_image');
        }

        return redirect()->route('admin.categories.index')->withFlashSuccess(__('The Category is successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\Blog\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if (!Gate::allows('category_delete')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->withFlashSuccess(__('The Category was successfully deleted.'));
    }
}
