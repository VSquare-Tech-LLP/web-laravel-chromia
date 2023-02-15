@extends('frontend.layouts.app')

@php
    $title = (config('app.name') && config('app.tagline'))?config('app.name').' - '.config('app.tagline'):appName();
    $meta_title = $title;
    $description = '';
    $imageurl = appOgImage();
    $pageurl = route('frontend.index');
    $siteurl = config('app.url');
    $locale = getStandardLocaleName(Config::get('app.locale'));
@endphp

@section('title',$title)

@section('meta')
@endsection

@push('after-styles')
@endpush

@section('content')
    <div class="container">
        <div class="vh-100"></div>
    </div>
@endsection

