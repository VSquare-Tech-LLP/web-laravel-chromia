@extends('frontend.layouts.app')
@php
    $title = $user->name.', Author on '.appName();
    $description = '';
    $imageurl = $user->userMeta->image_path ?? '';
    $pageurl = route('frontend.single-author', ['username' => $user->username]);
    $siteurl = config('app.url');
    $locale = getStandardLocaleName(Config::get('app.locale'));
@endphp
@section('title',$title)
{{-- @section('meta_description',$description) --}}
{{-- @section('author',$user->name) --}}
@section('meta')
    <meta name="robots" content="noindex, follow">
    <meta name="author" content="{{$user->name}}">
    <meta property="og:title" content="{{ isset($title)?$title : appName().'- Article' }}"/>
    <meta property="og:locale" content="{{$locale}}" />
    <meta property="og:type" content="profile"/>
    <meta property="og:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    <meta property="og:url" content="{{isset($pageurl)?$pageurl:""}}"/>
    {{-- <meta property="og:description" content="{{ isset($description)?$description:"" }}"/> --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($title)?$title: appName().'- Article' }}"/>
    {{-- <meta name="twitter:description" content="{{ isset($description)?$description:"" }}"/> --}}
    <meta name="twitter:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    @if($user_posts->links())
    @if($user_posts->nextPageUrl())
<link rel="next" href="{{ $user_posts->nextPageUrl() }}"/>
    @endif
    @if($user_posts->previousPageUrl())
    @if($user_posts->currentPage() == 2)
<link rel="prev" href="{{$pageurl}}"/>
    @else
<link rel="prev" href="{{ $user_posts->previousPageUrl() }}"/>
    @endif
    @endif
    @endif
    <link rel="canonical" href="{{$pageurl}}"/>
    <link rel="alternate" type="application/rss+xml" title="{{config('app.name')}} Feed" href="{{route('frontend.feed')}}" />
    <script type="application/ld+json" class="schema-graph">
        {
            "@context": "https://schema.org",
            "@graph": [
                {
                "@type": "WebSite",
                "@id": "{{$siteurl}}#website",
                "url": "{{$siteurl}}",
                "name": "{{appName()}}",
                "description": "",
                "potentialAction": [
                    {
                    "@type": "SearchAction",
                    "target": {
                        "@type": "EntryPoint",
                        "urlTemplate": "{{$siteurl}}/search/?q={search_term_string}"
                    },
                    "query-input": "required name=search_term_string"
                    }
                ],
                "inLanguage": "{{$locale}}"
                },
                {
                "@type": "ProfilePage",
                "@id": "{{$pageurl}}#webpage",
                "url": "{{$pageurl}}",
                "name": "{{$title}}",
                "isPartOf": {
                    "@id": "{{$siteurl}}#website"
                },
                "breadcrumb": {
                    "@id": "{{$pageurl}}#breadcrumb"
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
                "@id": "{{$pageurl}}#breadcrumb",
                "itemListElement": [
                    {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Home",
                    "item": "{{$siteurl}}"
                    },
                    {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "{{$user->name}} Archives"
                    }
                ]
                },
                {
                "@type": "Person",
                "name": "{{$user->name}}",
                "image": {
                    "@type": "ImageObject",
                    "@id": "{{$siteurl}}#personlogo",
                    "inLanguage": "{{$locale}}",
                    "url": "{{$imageurl}}",
                    "contentUrl": "{{$imageurl}}",
                    "caption": "{{$user->name}}"
                },
                "description": "{{$user->short_bio}}",
                "mainEntityOfPage": {
                    "@id": "{{$pageurl}}#webpage"
                }
                }
            ]
        }
    </script>
@endsection
@push('after-styles')
    <link href="{{ mix('css/author-page.css') }}" rel="stylesheet">
@endpush
@section('content')
    <section class="hero-content ">
        <div class="container ">
            <div class="row">
                <div class="col-lg-7 col-12 text-center mb-2 mx-auto">
                    <img class="author-image d-inline" src="{{ $user->userMeta->image_path ?? asset('img/user-icon.png') }}" height="90px" width="90px">
                    <h1 class="hero-title d-inline">{{$user->name}}</h1>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-11 col-12 mt-3 mx-auto text-justify">
                    <p class="hero-description">{!! $user->userMeta->long_bio ?? "" !!}</p>
                </div>

            </div>
        </div>
    </section>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="content-title">Posts by {{$user->name}}</p>
                </div>
            </div>
            <div class="row">
                @foreach($user_posts as $post)
                    @include('frontend.includes.partials.post-card',compact('post'))
                @endforeach
                @include('frontend.includes.partials.pagination', ['paginator' => $user_posts])
            </div>
            {{--@include('frontend.includes.partials.more-categories',['heading'=>'Explore More Categories','categories'=>$categories])--}}
        </div>
    </section>
@endsection

@push('after-scripts')
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script>
    $(function() {
        var print = function(msg) {
            alert(msg);
        };

        var setInvisible = function(elem) {
            elem.css('visibility', 'hidden');
        };
        var setVisible = function(elem) {
            elem.css('visibility', 'visible');
        };

        var elem = $("#sticky-navbar");
        var items = elem.children();
        var outer = $('#sticky-nav-wrapper');



        var updateUI = function () {
            var maxWidth = $('#sticky-nav-wrapper').outerWidth(true);
            var actualWidth = 0;
            $.each($('#sticky-navbar >'), function (i, item) {
                actualWidth += $(item).outerWidth(true);
            });

            if (actualWidth <= maxWidth) {
                setVisible($('#left-button'));
            }
        };
        updateUI();


        $('#right-button').click(function (e) {
            e.preventDefault();

            var leftPos = $('#sticky-navbar').scrollLeft();
            $('#sticky-navbar').animate({
                scrollLeft: leftPos - 300
            }, 500, function () {
                if ($('#sticky-navbar').scrollLeft() <= 0) {
                    setInvisible($('#right-button'));
                }
            });
        });

        $('#left-button').click(function (e) {
            e.preventDefault();
            setVisible($('#right-button'));
            var leftPos = $('#sticky-navbar').scrollLeft();
            $('#sticky-navbar').animate({
                scrollLeft: leftPos + 300
            }, 500);
        });
        $(window).resize(function() {
            updateUI();
        });

        $('.list-inline-item a').on('click',function(){
            $('.list-inline-item').removeClass('active')
           $('.list-inline-item a').removeClass('active').attr('aria-selected',false);
           $(this).parent('.list-inline-item').addClass('active')
        });
    });

</script>
@endpush
