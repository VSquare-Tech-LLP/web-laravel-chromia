@extends('backend.layouts.app')

@section('title', __('General Setting'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Cache Settings')
        </x-slot>
        <x-slot name="body">
            <div class="form-group">
                <x-forms.post :action="route('admin.settings.cache-purge')">
                <label for="perge_url" class="form-control-label font-weight-bold">@lang('Perge a Url') </label>
                <div class="input-group mb-3">   
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{url('/').'/'}}</span>
                    </div> 
                    <input id="perge_url" type="text" name="perge_url" class="form-control"/>
                    <button class="btn btn-sm btn-primary" type="submit">@lang('Purge')</button>
                </div>            
                </x-forms.post>
                <x-forms.post :action="route('admin.settings.cache-purge-all')">
                <label for="puge_all" class="form-control-label font-weight-bold">@lang('Perge All') </label>
                <button class="btn btn-sm btn-danger" id="puge_all" name="puge_all" type="submit">@lang('Purge All')</button>
                </x-forms.post>
            </div>
        </x-slot>
        <x-slot name="footer">
               
        </x-slot>
    </x-backend.card>
@endsection
