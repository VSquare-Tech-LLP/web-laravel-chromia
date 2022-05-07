@extends('backend.layouts.app')

@section('title', __('Create Tag'))

@section('content')
    <x-forms.post :action="route('admin.tags.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Tag')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.tags.index')" :text="__('Cancel')" />
            </x-slot>

            @include('backend.blog.tag.form',['tag' => optional()])

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Tag')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
