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
        <div class=" my-5 d-flex align-items-center justify-content-center">
            <div class="wrapper d-flex flex-column">
                <div class="col-lg-8 col-12 mx-auto">
                <h1 class="text-center">AI Face Swap: Effortless Face Swapping with 1000+ Templates</h1>

                <a class="mx-auto text-center d-block my-4" href="https://apps.apple.com/app/ai-face-swap-photo-changer/id6483924698">
                    <img class="mx-auto" src="{{asset('download-link.png')}}" width="300">
                </a>

                <p>Transform your photos with AI Face Swap, the ultimate app for creating stunning face swaps with ease! Whether you want to have fun with friends or create unique digital art, AI Face Swap is your go-to app.</p>

                <p>Key Features:</p>

                <h3>1000+ Templates:</h3>
                <p>Choose from a vast collection of face swap templates to find the perfect match for any occasion.</p>


                <h3>Upload Your Own Templates:</h3>
                <p>Customize your swaps by uploading your own templates and making the magic happen.</p>


                    <h3>HD Downloads:</h3>
                <p>Download and share your face-swapped photos in high-definition for the best quality.</p>


                        <h3>Easy to Use:</h3>
                <p>Our intuitive interface makes face swapping a breeze. Simply select a template or upload your own, and let the AI do the rest.
                Notifications: Get notified when your face swap is complete, so you can see the results instantly.</p>


                <p>Unlock endless fun and creativity with AI Face Swap.
                    Download now and start swapping faces like a pro!</p>


                <p>Fore more details Contact Us at <a href="mailto:tankariyapankaj@gmail.com">tankariyapankaj@gmail.com</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

