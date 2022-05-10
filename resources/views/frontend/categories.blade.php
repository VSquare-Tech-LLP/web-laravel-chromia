@extends('frontend.layouts.app')
@php
    $title = 'All categories';
    $meta_title = 'All categories';
    $pageurl = route('frontend.categories');
@endphp
@section('title',$title)
@section('meta_description',$description??'')
@section('meta')
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="{{ isset($title)?$title: env('APP_NAME').'- Article' }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="{{isset($imageurl)?$imageurl:url('/og-image.png')}}"/>
    <meta property="og:url" content="{{isset($pageurl)?$pageurl:""}}"/>
    <meta property="og:description" content="{{ isset($description)?$description:"" }}"/>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($title)?$title: env('APP_NAME').'- Article' }}"/>
    <meta name="twitter:description" content="{{ isset($description)?$description:"" }}"/>
    <meta name="twitter:image" content="{{isset($imageurl)?$imageurl:url('/og-image.png')}}"/>
    <link rel="canonical" href="{{$pageurl}}"/>
@endsection
@push('after-styles')
    <link href="{{ mix('css/category-page.css') }}" rel="stylesheet">
@endpush
@section('content')
    <section class="category-box-wrappper vh-100">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-4">
                    <h1 class="text-center">Every Top10 Shortlist, all in one place</h1>
                </div>
                <div class="col-12">
                    @foreach($topCategories as $category)
                    <a href="{{route('frontend.single-category',['slug'=>$category->slug])}}">
                        <div class="category-box">
                            {{-- <img alt="Lifestyle" class="category-image" src="{{url('/img/lifestyle.svg')}}"> --}}
                            <p class="mb-0 category-name" >{{$category->name}}</p>
                        </div>
                    </a>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
    <section class="category-data-wrapper">
        <div class="container">
            @foreach($topCategories as $tCategory)
            @if($tCategory->children->count() < 1)
                @continue
            @endif
            <div class="row mb-5">
                <div class="col-12">
                    <p class="title separator">{{$tCategory->name}}</p>
                </div>
                @foreach($tCategory->children as $child)
                {{-- <a href="#" class="col-lg-2 col-6">{{$child->name}}</a> --}}
                    @include('frontend.includes.partials.child-category',['childCategory'=>$child])
                @endforeach
            </div>
            @endforeach
        </div>
    </section>
@endsection

@push('after-scripts')

@endpush
