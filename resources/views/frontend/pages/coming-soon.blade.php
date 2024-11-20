@extends('frontend.layouts.app')
@php
    $title = 'Coming Soon';
    $description='';
    $pageurl = route('frontend.contact-us');
@endphp
@section('title',$title)

@section('meta')
    <meta name="description" content="{{isset($description)?$description:""}}">

    <meta name="robots" content="no-index, nofollow">
    <meta property="og:title" content="{{ $title }}"/>
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
    <style>
        .close {
            border-radius: 50%;
            outline: none;
            border: none;
            width: 30px;
            color: #155724;

            background: transparent;
        }
        .hero{
            height:200px;
            display:flex;
            justify-content: center;
            align-content: center;
        }
        #alert {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .nametag {
            text-align: center;
        }
    </style>

@endpush
@section('content')

    <section class="hero ">
        <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 200px;background: #1921ff">
            <div class="row d-flex justify-content-center align-items-center" >
                <div class="col-md-12  mb-2">
                    <h2 class="heading text-center text-white">Coming Soon!</h2>
                </div>
            </div>
        </div>
    </section>
    <div class="container  contact-us py-3">
        <div class="row" style="min-height: 65vh">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="entry-content mt-4" itemprop="text">
                    <p class="nametag"> Stay tuned.</p>
                    <p class="nametag"> We are launching soon.</p>
                    <p class="nametag"> We are working hard.</p>
                    <p class="nametag"> We are almost ready to launch.</p>
                    <p class="nametag"> Something awesome is coming soon.</p>
                    <p class="nametag"> Be first to know.</p>
                </div>
            </div>
        </div>
    </div><!--container-->
@endsection

@push('after-scripts')
    <script>
        $('.close').on('click', function () {
            $('#alert').hide();
        })
    </script>

@endpush
