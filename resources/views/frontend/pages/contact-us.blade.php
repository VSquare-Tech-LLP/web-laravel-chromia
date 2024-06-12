@extends('frontend.layouts.app')
@php
    $title = 'Feel Free to Reach Out';
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
    </style>

@endpush
@section('content')

    <section class="hero ">
        <div class="container-fluid d-flex justify-content-center align-items-center" style="height: 200px;background: #1921ff">
            <div class="row d-flex justify-content-center align-items-center" >
                <div class="col-md-12  mb-2">
                    <h2 class="heading text-center text-white">Support</h2>
                </div>
            </div>
        </div>
    </section>
    <div class="container  contact-us py-3">
        <div class="row">
            <div class="col-lg-8 col-12 mx-auto">
                <div class="entry-content mt-4" itemprop="text">
                    <p>Our contact email: <a href="mailto:tankariyapankaj@gmail.com">tankariyapankaj@gmail.com</a></p>
                    <p>We would be glad to hear from you. Send in your requests, feedback, and questions by filling out the form:
                    </p>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-lg-8 col-12 mx-auto">

                @if(session()->has('success_message'))

                    <div id="alert" class=" pl-4 py-2 rounded font-weight-bold" role="alert">
                        {{session()->get('success_message')}}
                        <button type="button" class="close float-right" data-dismiss="alert"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
                <form method="post" class="mt-2" action="{{url('support')}}">
                    {{csrf_field()}}
                    <div class="row">
                        @if(auth()->guest())
                            <div class="form-group mb-2 col-12 col-md-6">
                                <div>
                                    <input type="text" value="{{old('name')}}" class="form-control"
                                           id="name" required name="name" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <div class="help-block text-danger text-left">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group mb-2  col-md-6 col-12">
                                <div>
                                    <input required type="email" class="form-control"
                                           value="{{old('email')}}" id="email" name="email"
                                           placeholder="Enter your email">
                                    @if ($errors->has('email'))
                                        <div class="help-block text-danger text-left">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        <div class="form-group mb-2  col-md-12 col-xs-12">
                            <div>
                                <input type="text" required value="{{old('subject')}}" class="form-control"
                                       id="subject" placeholder="Subject" name="subject">
                                @if ($errors->has('subject'))
                                    <div class="help-block text-danger text-left">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2  col-md-12 col-xs-12">
                            <div>
                                            <textarea rows="5" required class="form-control" id="message" name="message"
                                                      placeholder="Message">{{old('message')}}</textarea>
                                @if ($errors->has('message'))
                                    <div class="help-block text-danger text-left">
                                        <strong>{{ $errors->first('message') }}</strong>
                                    </div>
                                @endif
                            </div>
                            <input type="hidden" name="url" id="url" value="">
                        </div>

                            <div class="form-group col-12 text-center mx-auto">
                            <button class="text-uppercase btn-block btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
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
