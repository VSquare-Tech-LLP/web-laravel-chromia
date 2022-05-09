@extends('backend.layouts.app')

@section('title', __('General Setting'))

@section('content')
    <x-forms.post :action="route('admin.settings.store')">
        <x-backend.card>
            <x-slot name="header">
                @lang('Logo Settings')
            </x-slot>
            <x-slot name="body">
                <div class="form-group">
                    <label for="favicon_image" class="form-control-label font-weight-bold">@lang('Favicon Icon')</label>
                    <input type="file"
                           id="app__fevicon"
                           name="favicon_image"
                           class="form-control"
                           data-preview="#favicon_preview"
                           accept="image/jpeg,image/gif,image/png,image/ico"/>
                    <p>Note : Upload logo with resolution <b>32x32</b> pixels and extension <b>.png</b> or <b>.gif</b>
                        or
                        <b>.ico</b></p>
                </div>
                <div class="form-group">
                    <div id="favicon_preview" class="d-inline-block p-3 preview">
                        <img height="50px"
                             src="{{config('favicon_image')?asset('storage/logos/'.config('favicon_image')):''}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="og_image" class="form-control-label font-weight-bold">@lang('OG Image')</label>
                    <input type="file"
                           id="og_image"
                           name="og_image"
                           class="form-control"
                           data-preview="#og_image_preview"
                           accept="image/jpeg,image/png"/>
                    <p>Note : Upload logo with resolution <b>1200x627</b> pixels and extension <b>.png</b> or
                        <b>.gif</b> or
                        <b>.ico</b></p>
                </div>
                <div class="form-group">
                    <div id="og_image_preview" class="d-inline-block p-3 preview">
                        <img height="50px" src="{{config('og_image')?asset('storage/logos/'.config('og_image')):''}}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="site_logo" class="form-control-label font-weight-bold">@lang('Site Logo')</label>
                    <input type="file"
                           name="site_logo"
                           class="form-control"
                           data-preview="#logo_preview"
                           accept="image/jpeg,image/gif,image/png"/>
                    <p>Note : Upload logo with <b> transparent background in .png format</b> and <b>294x50</b>(WxH)
                        pixels.<br> <b>Height</b> should be fixed, <b>width</b> according to your <b>aspect ratio</b>
                    </p>
                </div>
                <div class="form-group">
                    <div id="logo_preview" class="d-inline-block p-3 preview">
                        <img height="50px" src="{{config('site_logo')?asset('storage/logos/'.config('site_logo')):''}}">
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save Settings')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>
@endsection

@push('after-scripts')
    <script>
        //========== Preview image function on upload =============//
        var previewImage = function (input, block) {
            var fileTypes = ['jpg', 'jpeg', 'png', 'gif', 'ico'];
            var extension = input.files[0].name.split('.').pop().toLowerCase();
            var isSuccess = fileTypes.indexOf(extension) > -1;

            if (isSuccess) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $(block).find('img').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                alert('Please input valid file!');
            }

        };
        $(document).on('change', 'input[type="file"]', function () {
            console.log($(this).data('preview'));
            previewImage(this, $(this).data('preview'));
        });
    </script>
@endpush
