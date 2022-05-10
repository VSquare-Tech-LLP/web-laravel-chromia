@extends('frontend.layouts.app')
@php
    $title = 'Latest posts';
    $meta_title = 'Latest posts';
    $pageurl = url('/latest-posts');
@endphp
@section('title',$title)
@section('meta')
    @if($posts->links())
        @if($posts->nextPageUrl())
            <link rel="next" href="{{ $posts->nextPageUrl() }}"/>
        @endif
        @if($posts->previousPageUrl())
            @if($posts->currentPage() == 2)
                <link rel="prev" href="{{$pageurl}}"/>
            @else
                <link rel="prev" href="{{ $posts->previousPageUrl() }}"/>
            @endif
        @endif
    @endif
    <meta name="robots" content="noindex, follow">
    <link rel="canonical" href="{{$pageurl}}"/>
@endsection
@push('after-styles')
    <link href="{{ mix('css/homepage.css') }}" rel="stylesheet">
    <link href="{{ mix('css/single-category-page.css') }}" rel="stylesheet">
@endpush
@section('content')

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="content-title">Latest Posts</p>
                </div>
                @foreach($posts as $post)
                    @include('frontend.includes.partials.post-card',compact('post'))
                @endforeach
                @include('frontend.includes.partials.pagination', ['paginator' => $posts])
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
