@extends('backend.layouts.app')

@section('title', __('Edit Caption'))

@section('content')
    <x-forms.post :action="route('admin.images.update',['image' => $image])">
        @method('PUT')
        <x-backend.card>
            <x-slot name="header">
                @lang('Edit Category Image')
            </x-slot>

            <x-slot name="headerActions">
                <x-utils.link class="card-header-action" :href="route('admin.images.index')" :text="__('Cancel')" />
            </x-slot>

            @include('backend.images.form',['image' => $image,'categorylist'=>$categorylist])

            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Update Image')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
   
@endsection
