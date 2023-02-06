<section class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-3 col-12">
                <div class="brand-name">
                    @if(config('site_logo'))
                        <a href="{{route('frontend.index')}}" class="logo-wrapper" aria-label="Logo">
                            <img src="{{asset('storage/logos/'.config('site_logo'))}}" height="30px" width="175px"
                                 alt="{{appName()}} Logo"></a>
                    @else
                        {{env('APP_NAME')}}
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<footer>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="mb-0">&copy; {{date('Y')}} {{config('app.name') ?? env('APP_NAME')}}</p>
            </div>
        </div>
    </div>
</footer>
