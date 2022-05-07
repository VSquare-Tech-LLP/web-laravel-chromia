@extends('backend.layouts.app')

@section('title', __('Category Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Tag Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.tags.create')"
                :text="__('Create Tag')"
            />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.blog.tags-table/>
        </x-slot>
    </x-backend.card>
@endsection
