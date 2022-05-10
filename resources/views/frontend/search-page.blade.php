@extends('frontend.layouts.app')
@php
    $title = 'Search results for - '.$search_term;
    $meta_title = 'Search results for - '.$search_term;;
    $pageurl = url('/search?q='.$search_term);
@endphp
@section('title',$title)
@section('meta')
    <meta name="robots" content="search?, noindex, follow">
    <link rel="canonical" href="{{$pageurl}}"/>
@endsection
@push('after-styles')
    <link href="{{ mix('css/homepage.css') }}" rel="stylesheet">
@endpush
@section('content')

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="content-title">{{$search_term}} - search results</p>
                </div>
                @if($posts->count())
                @foreach($posts as $post)
                    @include('frontend.includes.partials.post-card',compact('post'))
                @endforeach
                @include('frontend.includes.partials.pagination', ['paginator' => $posts])
                @else
                <div class="col-12" style="height:44vh;">
                    <p class="text-center">Nothing Found</p>
                </div>
                @endif
            </div>
            @if(!$categories->isEmpty())
            <section class="category-data-wrapper">
                <div class="container">
                    <div class="row mb-5">
                        <div class="col-12">
                            <p class="content-title separator">{{$search_term}} - category search results</p>
                        </div>
                        @foreach($categories as $category)
                        <a href="{{route('frontend.single-category',['slug'=>$category->slug])}}" class="col-lg-2 col-6">{{$category->name}}</a>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
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
