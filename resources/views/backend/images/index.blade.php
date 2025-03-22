@extends('backend.layouts.app')

@section('title', __('Category Images Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Category Images Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                    icon="c-icon cil-plus"
                    class="card-header-action"
                    :href="route('admin.images.create')"
                    :text="__('Add Category Image')"
            />
        </x-slot>

        <x-slot name="body">
            <livewire:backend.image-table />
        </x-slot>
    </x-backend.card>
@endsection
