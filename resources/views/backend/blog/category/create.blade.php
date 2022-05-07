@extends('backend.layouts.app')

@section('title', __('Create Category'))

@section('content')
    <x-forms.post :action="route('admin.categories.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Category')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.categories.index')" :text="__('Cancel')" />
            </x-slot>

            @include('backend.blog.category.form',['category' => optional()])

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Category')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
