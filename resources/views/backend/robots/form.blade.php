@extends('backend.layouts.app')

@section('title', __('Manage Robots.txt File'))

@section('content')
    <x-forms.post :action="route('admin.robots_file_write')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Manage Robots.txt File')
            </x-slot>
            <x-slot name="body">
                <div class="form-group row">
                    <label for="name" class="col-md-2 col-form-label">@lang('File')</label>

                    <div class="col-md-10">
                        <textarea name="robots_file" required class="form-control" rows="25"
                                  style="overflow-y: scroll">{{ $file }}</textarea>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('save')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection
