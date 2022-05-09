@extends('backend.layouts.app')

@section('title', __('Footer Setting'))

@section('content')
    <x-forms.post :action="route('admin.settings.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Footer Settings')
            </x-slot>
            <x-slot name="body">
                <div class="form-group">
                    <label for="about_section_title"
                           class="form-control-label font-weight-bold">@lang('About Section Title')</label>
                    <input id="about_section_title" type="text" name="footer__about_section_title" class="form-control"
                           value="{{ config('footer.about_section_title') }}"/>
                </div>
                <div class="form-group">
                    <label for="app__about" class="form-control-label font-weight-bold">@lang('App About')</label>
                    <textarea name="app__about" class="form-control">{{ config('app.about') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="latest_post_section_title"
                           class="form-control-label font-weight-bold">@lang('Latest Post Section Title')</label>
                    <input id="latest_post_section_title" type="text" name="footer__latest_post_section_title"
                           class="form-control"
                           value="{{ config('footer.latest_post_section_title') }}"/>
                </div>

                <div class="form-group">
                    <label for="important_links_section_title"
                           class="form-control-label font-weight-bold">@lang('Important Links Section Title')</label>
                    <input id="important_links_section_title" type="text" name="footer__important_links_section_title" class="form-control"
                           value="{{ config('footer.important_links_section_title') }}"/>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save Settings')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
