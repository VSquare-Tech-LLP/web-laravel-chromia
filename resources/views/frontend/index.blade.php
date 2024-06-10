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
        <div class="vh-100 d-flex align-items-center justify-content-center">
            <div class="wrapper d-flex flex-column">
                <h1> Welcome to AI FaceSwap </h1>
                <p>Fore more details Contact Us at tankariyapankaj@gmail.com</p>
            </div>
        </div>
    </div>
@endsection

