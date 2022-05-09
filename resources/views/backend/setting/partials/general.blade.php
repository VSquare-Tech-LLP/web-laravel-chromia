@extends('backend.layouts.app')

@section('title', __('General Setting'))

@section('content')
    <x-forms.post :action="route('admin.settings.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('General Settings')
            </x-slot>
            <x-slot name="body">

                <div class="form-group">
                    <label for="app__name" class="form-control-label font-weight-bold">@lang('App Name')</label>
                    <input type="text" name="app__name" class="form-control" value="{{ config('app.name') }}"/>
                </div>
                <div class="form-group">
                    <label for="app__url" class="form-control-label font-weight-bold">@lang('App URL')</label>
                    <input type="text" name="app__url" class="form-control" value="{{ config('app.url') }}"/>
                </div>
                <div class="form-group">
                    <label for="app__tagline" class="form-control-label font-weight-bold">@lang('App Tagline')</label>
                    <input type="text" name="app__tagline" class="form-control" value="{{ config('app.tagline') }}"/>
                </div>

                <div class="form-group">
                    <label for="app__google_analytics_id"
                           class="form-control-label font-weight-bold">@lang('Google Analytics Tracking ID')</label>
                    <input type="text" name="app__google_analytics_id" class="form-control"
                           value="{{ config('app.google_analytics_id') }}"/>
                </div>
                <div class="form-group row">
                    <label for="amp_status"
                           class="col-md-2 form-control-label font-weight-bold">@lang('AMP Status?')</label>
                    <div class="col-md-10">
                        <div class="form-row">
                            <div class="form-group col-md-1">
                                <label class="c-switch c-switch-label c-switch-pill c-switch-opposite-primary">
                                    <input class="c-switch-input" name="app__amp_status" type="checkbox"
                                        @if(config('app.amp_status',true)) checked @endif>
                                    <span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                </label>
                            </div>
                            <div class="form-group col-md-4">
                                <select name="app__amp_redirect_code" class="form-control inline-form-control">
                                <option value="0">Select Redirection if AMP is OFF</option>
                                @foreach(['1'=>"404",'2' => "301",'3' => '302'] as $key => $val)
                                    <option value="{{ $key }}"
                                            @if(config('app.amp_redirect_code',0) == $key) selected @endif> {{ $val }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <input type="hidden" name="general_page_setting" value="1">
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save Settings')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>

@endsection
