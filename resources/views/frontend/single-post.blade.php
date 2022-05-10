@extends('frontend.layouts.app')
@php
$title = $post->title;
$meta_title = $title;
$description = $post->meta_description;
$imageurl = $post->getFirstMediaUrl('featured_post_image');
$pageurl = route('frontend.single-post', ['slug' => $post->slug]);
$siteurl = config('app.url').'/';
$locale = getStandardLocaleName(Config::get('app.locale'));
@endphp
@section('title',$title)
@section('html_lang',$locale)
@section('meta')

    <meta name="author" content="{{$post->user->name}}">
    @if($post->type == 0 && isset($description) && $description)
    <meta name="description" content="{{ $description }}">
    @endif
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta property="og:title" content="{{ isset($title)?$title: env('APP_NAME').'- Article' }}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:locale" content="{{$locale}}" />
    <meta property="og:image" content="{{isset($imageurl)?$imageurl:url('/og-image.png')}}"/>
    <meta property="og:url" content="{{isset($pageurl)?$pageurl:""}}"/>
    @if($post->type == 0 && isset($description) && $description)<meta property="og:description" content="{{ $description }}"/>@endif{{-- <meta property="og:description" content="{{ isset($description)?$description:"" }}"/> --}}
    <meta property="og:site_name" content="{{config('app.name')}}" />
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($title)?$title: env('APP_NAME').'- Article' }}"/>
    @if($post->type == 0)<meta name="twitter:description" content="{{ isset($description)?$description:"" }}"/>@endif{{-- <meta name="twitter:description" content="{{ isset($description)?$description:"" }}"/> --}}
    <meta name="twitter:image" content="{{isset($imageurl)?$imageurl:url('/og-image.png')}}"/>
{{--    @if(config('app.amp_status', "on") == "on")--}}
{{--    <link rel="amphtml" href="{{route('frontend.single-post-amp', ['slug' => $post->slug])}}">--}}
{{--    @endif--}}
    <link rel="canonical" href="{{$pageurl}}"/>
    <link rel="alternate" type="application/rss+xml" title="{{config('app.name')}} Feed" href="{{route('frontend.feed')}}" />
    <link rel="alternate" type="application/rss+xml" title="{{$title}} Feed" href="{{route('frontend.single-feed',['slug' => $post->slug])}}" />
    @if($post->display_published_at)
<meta property="article:published_time" content="{{$post->display_published_at->toIso8601String()}}" />@endif
    <script type="application/ld+json">
      [
        {
          "@type": "Organization",
          "name": "{{appName()}}",
          "url": "{{$siteurl}}",
          @if(config('site_logo'))
          "logo": {
            "@type": "ImageObject",
            "url": "{{asset('storage/logos/'.config('site_logo'))}}",
            "caption": "{{appName()}} Logo"
          },
          @endif
          "@context": "http://schema.org"
        },
        {
          "@type": "WebSite",
          "name": "{{appName()}}",
          "url": "{{$siteurl}}",
          "potentialAction": {
            "@type": "SearchAction",
            "target": "{{$siteurl}}?s={search_term_string}",
            "query-input": "required name=search_term_string"
          },
          "@context": "http://schema.org"
        }
      ]
    </script>
    <script type="application/ld+json" class="schema-graph">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "WebSite",
          "@id": "{{$siteurl}}#Website",
          "url": "{{$siteurl}}",
          "name": "{{config('app.name')}}",
          "description": "{{config('app.tagline')}}",
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
          "@type": "ImageObject",
          "@id": "{{$pageurl}}/#primaryimage",
          "inLanguage": "{{$locale}}",
          "url": "{{isset($imageurl)?$imageurl:url('/og-image.png')}}",
          "width": 700,
          "height": 400
        },
        {
          "@type": "WebPage",
          "@id": "{{$pageurl}}/#webpage",
          "url": "{{$pageurl}}",
          "name": "{{ isset($title)?$title: env('APP_NAME').'- Article' }}",
          "isPartOf": {
            "@id": "{{$siteurl}}#website"
          },
          "primaryImageOfPage": {
            "@id": "{{$pageurl}}/#primaryimage"
          },@if($post->display_published_at)
          "datePublished": "{{$post->display_published_at->toIso8601String()}}",@endif
          "dateModified": "{{$post->updated_at->toIso8601String()}}",
          "author": {
            "@id": "{{$siteurl}}#/schema/person"
          },
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
            },
            {
              "@type": "ListItem",
              "position": 2,
              "item": {
                "@type": "WebPage",
                "@id": "{{$pageurl}}",
                "url": "{{$pageurl}}",
                "name": "{{$title}}"
              }
            }
          ]
        },
        {
          "@type": "Person",
          "@id": "{{$siteurl}}#/schema/person",
          "name": "{{$post->user->name}}",
          "image": {
            "@type": "ImageObject",
            "@id": "{{$siteurl}}#personlogo",
            "inLanguage": "{{$locale}}",
            "url": "{{$post->user->userMeta->image_path ?? asset('img/user-icon.png')}}",
            "caption": "{{$post->user->name}}"
          },
          "description": "{{$post->user->userMeta->short_bio ?? 'Author at '.config('app.name')}}",
          "sameAs": [
            "{{$siteurl}}"
          ]
        }
      ]
    }
    </script>
@endsection
@push('after-styles')
    <link href="{{ mix('css/blog-page.css') }}" rel="stylesheet">
    <style>
        .custom-control-label::before,
        .custom-control-label::after {
            top: .2rem;
            width: 1.25rem;
            left: -2.4em;
            height: 1.25rem;

        }

        section.hero-content .comment-form label.custom-control-label {
            font-size: 1em;
        }
    </style>
@endpush
@section('content')
    <div class="bg-top"></div>
    <section class="hero-content single-blog">
        <div class="container ">
            <div class="row position-relative">
                <div class="col-12 mx-auto px-lg-0">
                    <h1 class="blog-title">{{ $title }}</h1>
                    <div class="meta-data">
                        @if($post->user->userMeta)
                        <img height="35px" width="35px" class="author-image" src="{{ $post->user->userMeta->image_path }}" alt="{{ $post->user->name }}">
                        @endif
                        <span><a href="{{route('frontend.single-author',['username'=>$post->user->username])}}" rel="author" title="{{ $post->user->name }}" >{{ $post->user->name }}</a></span>/<span>{{ $post->display_published_at->format('F d, Y') }}</span>
                        @if($post->user->userMeta)
                         <p class="mt-2 mb-2"><span data-nosnippet>{{$post->user->userMeta->short_bio}}</span></p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>
    <section class="blog-content">
        <div class="container ">
            <div class="row position-relative">
                <div class="col-12 mx-auto px-lg-0">
                    <div class="featured-image">
                        @if($post->getFirstMedia('featured_post_image'))
                            <img src="{{$post->getFirstMediaUrl('featured_post_image')}}"
                                 alt="{{$title}}"
                                 width="720"
                                 height="409"
                                 srcset="{{$post->getFirstMediaUrl('featured_post_image','thumb')}} 385w,
                                            {{$post->getFirstMediaUrl('featured_post_image')}} 700w"
                                 sizes="(max-width: 465px) 385px,700px">
                        @endif

                    </div>



                    <div class="content">
                        {!! $post->body !!}

                    </div>

                <!--- Bottom Meta Data --->
                    <div class="bottom-meta-data">
                      @php($main_category = $post->mainCategory)
                        <a href="{{route('frontend.single-category',['slug' => $main_category->slug ])}}" class="category-link">{{$main_category->name}} </a> /
                        @foreach($post->categories->whereNotNull('parent_id')->whereNotIn('id',[$main_category->id]) as $post_cat)
                        <a href="{{route('frontend.single-category',['slug' => $post_cat->slug ])}}" class="sub-category-link">{{$post_cat->name}}</a>
                            @if(!$loop->last)
                                /
                            @endif
                        @endforeach
                    </div>
                    <!-- Author Bio -->
                    <div class="media bg-white p-3 rounded mb-4 flex-column flex-sm-row flex-md-row">
                       @if($post->user->userMeta)
                        <img class="align-self-center mr-0 mr-lg-3 mr-md-3 rounded " width="80"  height="80" src="{{ $post->user->userMeta->image_path }}" alt="{{ $post->user->name }}">
                       @endif
                      <div class="media-body">
                        <p class="font-weight-bold mb-0 text-lg-left text-md-left text-center"><a href="{{route('frontend.single-author',['username'=>$post->user->username])}}" rel="author" title="{{ $post->user->name }}" >{{ $post->user->name }}</a></p>
                        @if($post->user->userMeta)
                         <p class="mb-0 text-lg-left text-md-left text-center"><span class="w-100" data-nosnippet>{{$post->user->userMeta->short_bio}}</span></p>
                        @endif
                      </div>
                    </div>
                    <!--- Post Navigation Section ---->
                    <div class="post-navigation">
                        @if($prev_post && $prev_post_title=$prev_post->title)
                        <div class="previous">
                            <a href="{{route('frontend.single-post',['slug'=>$prev_post->slug])}}">
                                <p class="float-left mb-1">Previous Article</p>
                                <div class="article-card">
                                    <img class="lazyload" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" data-src="{{$prev_post->getFirstMediaUrl('featured_post_image','thumb')}}" alt="{{$prev_post_title}}">
                                    <p class="mb-0">{{$prev_post_title}}</p>
                                </div>
                            </a>
                        </div>
                        @endif
                        @if($next_post && $next_post_title=$next_post->title)
                        <div class="next">
                            <a href="{{route('frontend.single-post',['slug'=>$next_post->slug])}}">
                                <p class="float-right mb-1">Next Article</p>
                                <div class="article-card">
                                    <img class="lazyload" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC" data-src="{{$next_post->getFirstMediaUrl('featured_post_image','thumb')}}" alt="{{$next_post_title}}">
                                    <p class="mb-0">{{$next_post_title}}</p>
                                </div>
                            </a>
                        </div>
                        @endif
                    </div>
                    <!---- Related Post Section ---->
                    <div class="related-posts">
                        <div class="row">
                            @foreach($post_related_posts->take(3) as $related_post)
                                @include('frontend.includes.partials.post-card',['post' => $related_post ])
                            @endforeach
                        </div>

                    </div>

                    <!-- Comment Section ---->
                    <div class="comment-box">
                        <div class="subtitle">Comments</div>
                        @comments([
                        'model' => $post,
                        'approved' => true
                        ])

                    </div>

                </div>
            </div>
        </div>
    </section>
    <a class="to-top"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-up" class="svg-inline--fa fa-angle-up fa-w-10" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="20px" height="20px"><path fill="#fff" d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"></path></svg></a>
@endsection
