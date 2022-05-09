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
                    <label for="scripts__header" class="form-control-label font-weight-bold">@lang('Header Scripts')</label>
                    <textarea name="scripts__header" class="form-control">{{ config('scripts.header') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="scripts__footer" class="form-control-label font-weight-bold">@lang('Footer Scripts')</label>
                    <textarea name="scripts__footer" class="form-control">{{ config('scripts.footer') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="scripts__amp_style" class="form-control-label font-weight-bold">@lang('Amp Custom CSS') <span>[ Do not add script or style tag, just the css only. ]</span></label>
                    <textarea name="scripts__amp_style" class="form-control">{{ config('scripts.amp_style') }}</textarea>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save Settings')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
