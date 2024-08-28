@extends('frontend.layouts.app')

@php
    $title = "Ai Picture Generator";
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
                <h1 class="text-center">Ai Picture Generator</h1>

                <a class="mx-auto text-center d-block my-4" href="https://apps.apple.com/app/fluxmate-ai-art-maker/id6642674514">
                    <img class="mx-auto" src="{{asset('download-link.png')}}" width="300">
                </a>

                <p>Unleash your creativity with Ai Picture Generator, the ultimate AI art generator! Whether you’re an artist or just someone who loves
                exploring new forms of expression, Ai Picture Generator empowers you to create stunning visuals in seconds. Simply enter a prompt,
                select the size, and let our advanced AI turn your ideas into reality.</p>

                <p>Key Features:</p>

                <h3>Create Your Art:</h3>
                <p>Input a prompt, choose your preferred size, and watch as Ai Picture Generator transforms your words into beautiful images.</p>


                <h3>Explore Creative Prompts:</h3>
                <p>Stuck for ideas? Discover a wide range of inspiring prompts to kickstart your artistic journey.</p>

                <h3>Share & Download:</h3>
                <p>Easily share your creations with friends and family or download them directly to your device.</p>

                <h3>Unlimited Possibilities:</h3>
                <p>With Ai Picture Generator, the only limit is your imagination. Generate endless art pieces and explore new creative horizons.</p>


                <p>Download Ai Picture Generator now and start creating your own masterpieces today!</p>

                <p>Fore more details Contact Us at <a href="mailto:tankariyapankaj@gmail.com">tankariyapankaj@gmail.com</a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

