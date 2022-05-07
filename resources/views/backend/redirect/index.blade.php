@extends('backend.layouts.app')

@section('title', __('Redirects Management'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Redirects')
        </x-slot>


        <x-slot name="body">
            <x-forms.post :action="route('admin.redirects.store')" class="mb-4">
                <div class="row">
                    <div class="col">
                        <label for="from_url" class="font-weight-bold">@lang('From URL')</label>
                        <input type="from_url" class="form-control" name="from_url">
                    </div>
                    <div class="col">
                        <label for="to_url" class="font-weight-bold">@lang('To URL')</label>
                        <input type="text" class="form-control" name="to_url">
                    </div>
                    <div class="col">
                        <label for="status_code" class="font-weight-bold">@lang('Status Code')</label>
                        <select name="status_code" class="form-control">
                            <option value="301">301</option>
                            <option value="302">302</option>
                        </select>
                    </div>
                    <div class="col">
                        <button class="btn btn-primary mt-4" type="submit">@lang('Save')</button>
                    </div>
                </div>

            </x-forms.post>

            <livewire:backend.redirects-table/>

        </x-slot>
    </x-backend.card>
@endsection
