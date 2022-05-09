@extends('backend.layouts.app')

@section('title', __('labels.backend.forms.create'))

@section('content')
    <div class="card mt-5">
        <x-forms.post :action="route('admin.settings.store')">
            <x-backend.card>
                <x-slot name="header">
                    @lang('Home Page Setting')
                </x-slot>
                <x-slot name="body">
                    <div>
                        <div class="form-group row">
                            <label for="home__main_title" class="col-md-2 col-form-label">@lang('Main Title')</label>

                            <div class="col-md-10">
                                <input type="text" name="home__main_title" class="form-control" placeholder="{{ __('Main Title') }}" value="{{ old('home__main_title',config('home.main_title')) }}"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home__main_description" class="col-md-2 col-form-label">@lang('Main Description')</label>

                            <div class="col-md-10">
                                <textarea class="form-control" id="home__main_description" name="home__main_description" placeholder="{{ __('Main Description') }}">{{ old('home__main_description',config('home.main_description')) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="home__how_it_work" class="col-md-2 col-form-label">@lang('Ho It Work')</label>

                            <div class="col-md-10">
                                <input type="text" name="home__how_it_work" class="form-control" placeholder="{{ __('How It Work') }}" value="{{ old('home__how_it_work',config('home.how_it_work')) }}"/>
                            </div>
                        </div>

                        @for($i=1; $i<5; $i++)
                        <hr>
                        <h4 class="font-weight-bold">How It Work {{ $i }}</h4>

                        <div class="form-group row">
                            <label for="home__how_it_work_icon_{{$i}}" class="col-md-2 col-form-label">@lang('Icon')</label>

                            <div class="col-md-10">
                                <input type="file" name="home__how_it_work_icon_{{$i}}" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-2"></div>
                            <div class="col-md-10">
                                <img height="50px" src="{{config('home.how_it_work_icon_'.$i)?asset('storage/home/icons/'.config('home.how_it_work_icon_'.$i)):''}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="home__how_it_work_title_{{$i}}" class="col-md-2 col-form-label">@lang('Title')</label>

                            <div class="col-md-10">
                                <input type="text" name="home__how_it_work_title_{{$i}}" class="form-control" placeholder="{{ __('Title') }}" value="{{ old('home__how_it_work_title_'.$i, config('home.how_it_work_title_'.$i)) }}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="home__how_it_work_desc_{{ $i }}" class="col-md-2 col-form-label">@lang('Description')</label>

                            <div class="col-md-10">
                                <textarea class="form-control" name="home__how_it_work_desc_{{ $i }}" placeholder="{{ __('Description') }}">{{ old('home__how_it_work_desc_'.$i, config('home.how_it_work_desc_'.$i)) }}</textarea>
                            </div>
                        </div>
                        @endfor

                        <hr>
                        <div class="form-group row">
                            <label for="home__search_box_label" class="col-md-2 col-form-label">@lang('Search Box Label')</label>

                            <div class="col-md-10">
                                <input type="text" name="home__search_box_label" class="form-control" placeholder="{{ __('Search Box Label') }}" value="{{ old('home__search_box_label',config('home.search_box_label')) }}"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="home__latest_post_label" class="col-md-2 col-form-label">@lang('Latest Post Label')</label>

                            <div class="col-md-10">
                                <input type="text" name="home__latest_post_label" class="form-control" placeholder="{{ __('Latest Post Label') }}" value="{{ old('home__latest_post_label',config('home.latest_post_label')) }}"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="home__explore_categories_label" class="col-md-2 col-form-label">@lang('Explore Categories Label')</label>

                            <div class="col-md-10">
                                <input type="text" name="home__explore_categories_label" class="form-control" placeholder="{{ __('Explore Categories Label') }}" value="{{ old('home__explore_categories_label',config('home.explore_categories_label')) }}"/>
                            </div>
                        </div>
                        <hr>
                        <h4 class="font-weight-bold"> Meta Description </h4>
                        <div class="form-group row">
                            <label for="home__meta_description" class="col-md-2 col-form-label">@lang('Meta Description')</label>

                            <div class="col-md-10">
                                <textarea class="form-control" id="home__meta_description" name="home__meta_description" placeholder="{{ __('Meta Description') }}">{{ old('home__meta_description',config('home.meta_description')) }}</textarea>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="footer">
                    <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
                </x-slot>
            </x-backend.card>
        </x-forms.post>
    </div>

@endsection
