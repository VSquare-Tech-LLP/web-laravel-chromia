@extends('frontend.layouts.app')
@php
    $title = $page->title;
    (config('app.name')) ? $title.=' - '.config('app.name') : '';
    $description = $page->meta_description ?? null;
    $imageurl = $page->getFirstMediaUrl('featured_post_image') ?? appOgImage();
    $pageurl = route('frontend.single-post', ['slug' => $page->slug]);
    $locale = getStandardLocaleName(Config::get('app.locale'));
@endphp
@section('title',$title)
@section('html_lang',$locale)
{{-- @section('meta_description',$description)
@section('author',$page->user->name) --}}
@section('meta')

    @if($page->meta && $page->meta->index_status == 0)
    <meta name="robots" content="noindex, follow">
    @else
    <meta name="robots" content="index, follow">
    @endif

    @if(isset($description) && $description)
    <meta name="description" content="{{ $description }}">
    @endif

    <meta property="og:title" content="{{ isset($title)?$title: appName().'- Article' }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:locale" content="{{$locale}}" />

    @if(isset($imageurl) && $imageurl)
    <meta property="og:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    @endif
    <meta property="og:url" content="{{isset($pageurl)?$pageurl:""}}"/>
    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($title)?$title: env('APP_NAME').'- Article' }}"/>

    @if(isset($imageurl) && $imageurl)
    <meta name="twitter:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    @endif

    <link rel="canonical" href="{{$pageurl}}"/>
@endsection
@push('after-styles')
@endpush
@section('content')
    <div class="container-fluid py-5 bg-primary text-white">
        <div class="row justify-content-center">
            <div class="col-md-12 mb-2">
            <h2 class="text-center font-weight-bold">{{ $page->title }}</h2>
            </div>
        </div>
    </div>
    <div class="container py-5 page-container">
        <div class="row">
            <div class="col-md-12">
                {!! do_shortcode($page->body) !!}
            </div>
        </div>
    </div>
@endsection
