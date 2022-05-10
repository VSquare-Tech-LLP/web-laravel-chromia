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
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta property="og:title" content="{{ isset($title)?$title: appName().'- Article' }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:locale" content="{{$locale}}" />
    <meta property="og:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    <meta property="og:url" content="{{isset($pageurl)?$pageurl:""}}"/>
    {{-- <meta property="og:description" content="{{ isset($description)?$description:"" }}"/> --}}
    @if(config('home.meta_description'))
        <meta name="description" content="{{ config('home.meta_description') }}">
        <meta property="og:description" content="{{ config('home.meta_description') }}"/>
    @endif
    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($title)?$title: appName().'- Article' }}"/>
    {{-- <meta name="twitter:description" content="{{ isset($description)?$description:"" }}"/> --}}
    <meta name="twitter:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    <link rel="canonical" href="{{$pageurl}}"/>
    <link rel="alternate" type="application/rss+xml" title="{{config('app.name')}} Feed" href="{{route('frontend.feed')}}" />
    <meta property="article:modified_time" content="{{ \Carbon\Carbon::today()->toIso8601String()}}" />
    @if(\App\Models\Blog\Post::count())
        <script type="application/ld+json" class="schema-graph">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "@id": "{{$siteurl}}#Website",
          "url": "{{$siteurl}}",
          "name": "{{config('app.name')}}",
          "description": "{{config('app.tagline')}}         ",
          "potentialAction": [
            {
              "@type": "SearchAction",
              "target": "{{$siteurl}}/search/?q={search_term_string}",
              "query-input": "required name=search_term_string"
            }
          ],
          "inLanguage": "{{$locale}}"
        },
        {
          "@type": "WebPage",
          "@id": "{{$pageurl}}/#webpage",
          "url": "{{$pageurl}}",
          "name": "{{ isset($title)?$title: env('APP_NAME').'- Article' }}",
          "isPartOf": {
            "@id": "{{$siteurl}}#website"
          },
          "datePublished": "{{\App\Models\Blog\Post::oldest()->first()->created_at->toIso8601String()}}",
          "dateModified": "{{ \Carbon\Carbon::today()->toIso8601String()}}",
          "breadcrumb": {
            "@id": "{{$pageurl}}/#breadcrumb"
          },
          "inLanguage": "{{$locale}}",
          "potentialAction": [
            {
              "@type": "ReadAction",
              "target": [
                "{{$pageurl}}"
              ]
            }
          ]
        },
        {
          "@type": "BreadcrumbList",
          "@id": "{{$pageurl}}/#breadcrumb",
          "itemListElement": [
            {
              "@type": "ListItem",
              "position": 1,
              "item": {
                "@type": "WebPage",
                "@id": "{{$siteurl}}",
                "url": "{{$siteurl}}",
                "name": "Home"
              }
            }
          ]
        }
      ]
    }
    </script>
    @endif
@endsection

@push('after-styles')
    <link href="{{ mix('css/homepage.css') }}" rel="stylesheet">
@endpush

@section('content')
    <section class="hero-content ">
        <div class="container ">

            <div class="row">
                <div class="col-lg-7 col-12 mx-auto">
                    <h1 class="hero-title text-center">{{ config('home.main_title') }}</h1>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-lg-11 col-12 mx-auto">
                    <p class="hero-description">{{ config('home.main_description') }}</p>
                </div>
            </div>
            <div class="row position-relative">
                <div class="col-lg-5 col-md-5 col-12 mt-4 mx-auto">
                    <div class="hero-search position-relative mb-3">
                        <form action="{{route('frontend.search')}}" method="GET">
                            <input type="text" name="q" class="form-control" placeholder="{{config('home.search_box_label','Search Here')}}">
                        </form>
                        <div class="search-icon">
                            <svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 width="1em"
                                 height="1em">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M208 48c-88.366 0-160 71.634-160 160s71.634 160 160 160 160-71.634 160-160S296.366 48 208 48zM0 208C0 93.125 93.125 0 208 0s208 93.125 208 208c0 48.741-16.765 93.566-44.843 129.024l133.826 134.018c9.366 9.379 9.355 24.575-.025 33.941-9.379 9.366-24.575 9.355-33.941-.025L337.238 370.987C301.747 399.167 256.839 416 208 416 93.125 416 0 322.875 0 208z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row hiw-wrapper">
                <div class="col-12">
                    <p class="content-title">{{ config('home.how_it_work') }}</p>
                </div>
                @for($i=1; $i<5; $i++)
                    <div class="col-lg-3 col-md-6 hiw-box col-12">
                        <div class="hiw-image">
                            <img src="{{config('home.how_it_work_icon_'.$i)?asset('storage/home/icons/'.config('home.how_it_work_icon_'.$i)):''}}">

                        </div>
                        <div class="hiw-body">
                            <p class="hiw-title">{{ config('home.how_it_work_title_'.$i) }}</p>
                            <p class="hiw-description">{{ config('home.how_it_work_desc_'.$i) }}</p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="content-title">{{ config('home.latest_post_label','Latest Posts') }}</p>
                </div>
                @foreach($latest_posts as $post)
                    @include('frontend.includes.partials.post-card',compact('post'))
                @endforeach
                @include('frontend.includes.partials.pagination', ['paginator' => $latest_posts])
            </div>
        </div>
    </section>
@endsection

