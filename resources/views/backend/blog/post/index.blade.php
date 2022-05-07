@extends('backend.layouts.app')

@section('title', __('Post Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Post Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.posts.create')"
                :text="__('Create Post')"
            />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.blog.posts-table/>
        </x-slot>
    </x-backend.card>
@endsection
