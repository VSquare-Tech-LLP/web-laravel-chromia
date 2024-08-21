<nav class="navbar navbar-expand-md ">
    <div class="container position-relative">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('Toggle navigation')">
            <span class="navbar-toggler-icon">
                <svg fill="white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                     viewBox="0 0 384 384" style="enable-background:new 0 0 384 384;" xml:space="preserve">
                    <g>
                        <g>
                            <g>
                                <rect y="277.3" width="248.6" height="42.7"/>
                                <rect y="170.7" width="384" height="42.7"/>
                                <rect y="64" width="384" height="42.7"/>
                            </g>
                        </g>
                    </g>
                </svg>
            </span>
        </button>
        @if(config('site_logo'))
            <a href="{{route('frontend.index')}}" class="logo-wrapper mxnavbar-brand mr-auto-auto" aria-label="Logo">
                <img src="{{asset('storage/logos/'.config('site_logo'))}}" height="30px" width="175px" alt="{{appName()}} Logo"></a>
        @else
            <x-utils.link
                :href="route('frontend.index')"
                :text="appName()"
                class="navbar-brand mx-auto" />
        @endif



        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mr-lg-4">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('support') }}">{{ __('Support') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://flux.eyuva.xyz/storage/privacy-policy.pdf">{{ __('Privacy') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://flux.eyuva.xyz/storage/terms-of-use.pdf">{{ __('Terms & Conditions') }}</a>
                </li>
                @guest
{{--                    @if (Route::has('frontend.auth.login'))--}}
{{--                        <li class="nav-item">--}}
{{--                            <a class="nav-link" href="{{ route('frontend.auth.login') }}">{{ __('Login') }}</a>--}}
{{--                        </li>--}}
{{--                    @endif--}}
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}"> Dashboard </a>
                            <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('frontend.auth.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div><!--navbar-collapse-->
    </div><!--container-->
</nav>

@push('after-scripts')
    <script>
        $(".btn-group, .dropdown").hover(
            function () {
                $('>.dropdown-menu', this).stop(true, true).fadeIn("fast");
                $(this).addClass('open');
            },
            function () {
                $('>.dropdown-menu', this).stop(true, true).fadeOut("fast");
                $(this).removeClass('open');
            });
    </script>
@endpush
