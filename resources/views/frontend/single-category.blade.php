@extends('frontend.layouts.app')
@php
    $title = $the_category->name.' Archives - '.appName();
    $pageurl = route('frontend.single-category', ['slug' => $the_category->slug]);
    $locale = getStandardLocaleName(Config::get('app.locale'));
    $imageurl = appOgImage();
@endphp
@section('title',$title)
@section('meta')
    <meta name="robots" content="noindex, follow">
    <meta property="og:title" content="{{ isset($title)?$title: appName().'- Article' }}"/>
    <meta property="og:locale" content="{{$locale}}" />
    <meta property="og:type" content="article"/>
    @if(isset($imageurl) && $imageurl)
<meta property="og:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    @endif
    <meta property="og:url" content="{{isset($pageurl)?$pageurl:""}}"/>
    {{-- <meta property="og:description" content="{{ isset($description)?$description:"" }}"/> --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($title)?$title: appName().'- Article' }}"/>
    {{-- <meta name="twitter:description" content="{{ isset($description)?$description:"" }}"/> --}}
    <meta name="twitter:image" content="{{isset($imageurl)?$imageurl:appOgImage()}}"/>
    @if($category_posts->links())
        @if($category_posts->nextPageUrl())
        <link rel="next" href="{{ $category_posts->nextPageUrl() }}"/>
        @endif
        @if($category_posts->previousPageUrl())
            @if($category_posts->currentPage() == 2)
        <link rel="prev" href="{{$pageurl}}"/>
            @else
        <link rel="prev" href="{{ $category_posts->previousPageUrl() }}"/>
            @endif
        @endif
    @endif
    <link rel="canonical" href="{{$pageurl}}"/>
@endsection
@push('after-styles')
    <link href="{{ mix('css/single-category-page.css') }}" rel="stylesheet">
@endpush
@section('content')

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <nav class="breadcrumb-wrapper" aria-label="breadcrumb">
                        <ol class="breadcrumb">

                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            {!! $the_category->getBreadCrumbLinks($the_category) !!}
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <p class="content-title">{{$the_category->name}}</p>
                </div>
            </div>
            @if($the_category->has('children'))
                <div class="subcategory-wrapper">
                    <div class="row mb-5">
                            @foreach($the_category->children as  $childCategory)
                                @include('frontend.includes.partials.child-category-pill', ['childCategory' => $childCategory,'class'=>'pill m-1'])
                            @endforeach
                    </div>
                </div>
            @endif
            <div class="row">
                @foreach($category_posts as $post)
                    @include('frontend.includes.partials.post-card',compact('post'))
                @endforeach
                @include('frontend.includes.partials.pagination', ['paginator' => $category_posts])
            </div>
            @include('frontend.includes.partials.more-categories',['heading'=>'Explore More Categories','categories'=>$categories])
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
