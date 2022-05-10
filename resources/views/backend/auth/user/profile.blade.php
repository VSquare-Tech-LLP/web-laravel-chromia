@extends('backend.layouts.app')

@section('title', __('User Metas'))

@section('content')
    <x-forms.post :action="route('admin.post.profile',['user' => $user])">
        <x-backend.card>
            <x-slot name="header">
                @lang('User Profile')
            </x-slot>
            @if(request()->get('back'))
            <x-slot name="headerActions">
                <x-utils.link class="card-header-action btn btn-info text-white" :href="route('admin.auth.user.index')" :text="__('Back User Management')" />
            </x-slot>
            @endif
            <x-slot name="body">
                <div>
                    <div class="form-group">
                        <label for="short_bio" class="form-control-label font-weight-bold">@lang('Shor Bio')</label>
                        <textarea name="short_bio" class="form-control">{{ old('short_bio',$userMetas->short_bio) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="long_bio" class="form-control-label font-weight-bold">@lang('Long Bio')</label>
                        <textarea name="long_bio" class="form-control">{{ old('short_bio',$userMetas->long_bio) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="image" class="form-control-label font-weight-bold">@lang('Image')</label>
                        <input type="file" name="image" class="form-control" accept="image/jpeg,image/gif,image/png"/>
                    </div>
                    @if($userMetas->image)
                    <div class="form-group">
                        <img src="{{ $userMetas->image_path }}" width="80">
                    </div>
                    @endif
                </div>
            </x-slot>
            <x-slot name="footer">
                <button class="btn btn-sm btn-primary" type="submit">@lang('Save')</button>
            </x-slot>
        </x-backend.card>
    </x-forms.post>

@endsection

