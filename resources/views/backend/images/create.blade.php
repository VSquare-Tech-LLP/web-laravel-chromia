@extends('backend.layouts.app')

@section('title', __('Create Meme Image'))

@section('content')
    <x-forms.post :action="route('admin.images.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Create Meme Image')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.images.index')" :text="__('Cancel')" />
            </x-slot>


            @include('backend.images.form',['image' => optional(),'categorylist'=>$categorylist])

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Create Category Image')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
