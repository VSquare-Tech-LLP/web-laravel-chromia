@extends('backend.layouts.app')

@section('title', __('Page Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Page Management')
        </x-slot>

        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.pages.create')"
                :text="__('Create Page')"
            />
        </x-slot>

        <x-slot name="body">

            <livewire:backend.pages-table/>
        </x-slot>
    </x-backend.card>
@endsection
