@extends('backend.layouts.app')

@section('title', __('General Setting'))

@section('content')
    <x-forms.post :action="route('admin.settings.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Color Settings')
            </x-slot>
            <x-slot name="body">
                <div class="form-group">
                    <label for="primary_color" class="form-control-label font-weight-bold">@lang('Primary Color') <small
                            class="text-muted font-italic">Default : #1921ff</small></label>
                    <input id="primary_color" type="text" name="settings_primary_color" class="form-control"
                           value="{{ config('settings_primary_color') }}"/>
                </div>

                <div class="form-group">
                    <label for="secondary_color" class="form-control-label font-weight-bold">@lang('Secondary Color')
                        <small class="text-muted font-italic">Default : #f8fafc</small></label>
                    <input id="secondary_color" type="text" name="settings_secondary_color" class="form-control"
                           value="{{ config('settings_secondary_color') }}"/>
                </div>

                <div class="form-group">
                    <label for="font_color" class="form-control-label font-weight-bold">@lang('Font Color') <small
                            class="text-muted font-italic">Default : #303030</small></label>
                    <input id="font_color" type="text" name="settings_font_color" class="form-control"
                           value="{{ config('settings_font_color') }}"/>
                </div>

                <div class="form-group">
                    <label for="link_color" class="form-control-label font-weight-bold">@lang('Link Color') <small
                            class="text-muted font-italic">Default : #303030</small></label>
                    <input id="link_color" type="text" name="settings_link_color" class="form-control"
                           value="{{ config('settings_link_color') }}"/>
                </div>


                <div class="form-group">
                    <label for="link_hover_color" class="form-control-label font-weight-bold">@lang('Link Hover Color')
                        <small class=" font-italic">Default : Primary Color</small></label>
                    <input id="link_hover_color" type="text" name="settings_link_hover_color" class="form-control"
                           value="{{ config('settings_link_hover_color') }}"/>
                </div>

                <div class="form-group">
                    <label for="link_color" class="form-control-label font-weight-bold">@lang('Content Link Color') <small
                            class="text-muted font-italic">Default : Primary Color</small></label>
                    <input id="link_color" type="text" name="settings_content_link_color" class="form-control"
                           value="{{ config('settings_content_link_color') }}"/>
                </div>


                <div class="form-group">
                    <label for="link_hover_color" class="form-control-label font-weight-bold">@lang('Content Link Hover Color')
                        <small class=" font-italic">Default : Primary Color</small></label>
                    <input id="link_hover_color" type="text" name="settings_content_link_hover_color" class="form-control"
                           value="{{ config('settings_content_link_hover_color') }}"/>
                </div>

                <div class="form-group">
                    <label for="footer_bg_color"
                           class="form-control-label font-weight-bold">@lang('Footer Background Color') <small
                            class=" font-italic">Default : #171717</small></label>
                    <input id="footer_bg_color" type="text" name="settings_footer_bg_color" class="form-control"
                           value="{{ config('settings_footer_bg_color') }}"/>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save Settings')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
