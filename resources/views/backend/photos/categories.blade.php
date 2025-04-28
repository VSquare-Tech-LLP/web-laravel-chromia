@extends('backend.layouts.app')

@section('title', __('Manage Categories'))

@section('content')
<x-forms.post :action="route('admin.categories.store')" enctype="multipart/form-data" >
    <x-backend.card>
        <x-slot name="header">
            @lang('Manage Categories')
        </x-slot>
        <x-slot name="body">
            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">@lang('Category Name')</label>
                <div class="col-md-10">
                    <input type="text" name="name" class="form-control" @if(isset($category) && $category) value="{{ $category->name }}" @endif  required>
                    @if(isset($category) && $category)
                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                    @endif
                </div>
            </div>


            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">@lang('Image')</label>
                <div class="col-md-10">
                    <input type="file" name="image" class="form-control" />
                    @if(isset($category) && $category)
                    <input type="hidden" name="old_image" value="{{ $category->image }}">
                    @endif
                </div>
            </div>

            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">@lang('Is Featured')</label>
                <div class="col-md-10">
                    
                    <input type="radio" name="featured" id="featured" value="1" {{ isset($category) && $category->featured == 1 ? 'checked' : '' }} /> Yes
                    <input type="radio" name="featured" id="featured" value="0" {{ isset($category) && $category->featured == 1 ? '' : 'checked' }}  /> No
                    
                </div>
            </div>

            <div class="mb-3 row">
                <label for="name" class="col-md-2 col-form-label">@lang('Category Name')</label>
                <div class="col-md-10">
                   
                    <textarea class="form-control" rows="7" name="description" >@if(isset($category) && $category) {{ $category->description }} @endif</textarea>
                   
                </div>
            </div>
            
            <!-- Add any additional fields for category management here -->

            <!-- Display existing categories for editing -->
            <h5>Existing Categories:</h5>
            <ul>
                @foreach($categories as $category)
                <li class="pt-2 pb-2" >
                    {{ $category->name }} - 
                    <img src="{{ url('storage/category/'.$category->image) }}"  width="70" alt=" " />
                    <a class="btn btn-secondary btn-sm" href="{{ route('admin.categories.edit', $category->id) }}">Edit</a>
                    <a class="btn btn-danger btn-sm" href="{{ route('admin.category.delete', $category->id) }}">Delete</a>
                </li>
                @endforeach
            </ul>
        </x-slot>
        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save Category')</button>
        </x-slot>
    </x-backend.card>
</x-forms.post>
@endsection