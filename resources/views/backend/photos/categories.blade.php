@extends('backend.layouts.app')

@section('title', __('Manage Categories'))

@section('content')
<x-forms.post :action="route('admin.categories.store')">
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
            <!-- Add any additional fields for category management here -->

            <!-- Display existing categories for editing -->
            <h5>Existing Categories:</h5>
            <ul>
                @foreach($categories as $category)
                <li class="pt-2 pb-2" >{{ $category->name }} - <a class="btn btn-secondary btn-sm" href="{{ route('admin.categories.edit', $category->id) }}">Edit</a>
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