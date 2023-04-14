<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    {{--<meta name="description" content="@yield('meta_description', appName())">--}}
    {{-- <meta name="author" content="@yield('meta_author',  appName())"> --}}
    @if(config('favicon_image'))
    <link rel="icon" type="image/png" href="{{asset('storage/logos/'.config('favicon_image'))}}" />
    <meta name="msapplication-TileImage" content="{{asset('storage/logos/'.config('favicon_image'))}}" />
    @endif
    @yield('meta')

    @stack('before-styles')
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" media="print" onload="this.media='all'" />
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    @stack('after-styles')
    <style>
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu>.dropdown-menu {
            top: 0;
            left: 100%;
            margin-top: 0px;
            margin-left: 0px;
        }

        .dropdown-submenu:hover>.dropdown-menu {
            display: block;
        }
    </style>
    @if(env('CUSTOM_THEME_COLOR'))
    <style>
        :root {
            --primary-color: {
                    {
                    config('settings_primary_color') ?? '#1921ff'
                }
            }

            ;

            --secondary-color: {
                    {
                    config('settings_secondary_color') ?? '#f8fafc'
                }
            }

            ;

            --footer-bg: {
                    {
                    config('settings_footer_bg_color') ?? '#171717'
                }
            }

            ;

            --font-color: {
                    {
                    config('settings_font_color') ?? '#303030'
                }
            }

            ;

            --link-color: {
                    {
                    config('settings_link_color') ?? '#303030'
                }
            }

            ;

            --link-hover-color: {
                    {
                    config('settings_link_hover_color') ?? 'var(--primary-color)'
                }
            }

            ;

            --content-link-color: {
                    {
                    config('settings_content_link_color') ?? 'var(--primary-color)'
                }
            }

            ;

            --content-link-hover-color: {
                    {
                    config('settings_content_link_hover_color') ?? 'var(--primary-color)'
                }
            }

            ;
        }
    </style>
    @endif
    @if(config('scripts.header'))
    {!! config('scripts.header') !!}
    @endif
    @if(config('app.google_analytics_id'))
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', '{{config('
            app.google_analytics_id ')}}'
        ]);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
                'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();
    </script>
    @endif
</head>

<body>
    @include('includes.partials.logged-in-as')

    <div id="app">
        @include('frontend.includes.nav')
        @include('includes.partials.messages', ['backend' => false])

        <main>
            @yield('content')
        </main>
        @include('frontend.includes.footer')
    </div><!--app-->

    @stack('before-scripts')
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/lazyload@2.0.0-rc.2/lazyload.js"></script>
    <script>
        $("img.lazyload").lazyload();
    </script>
    @stack('after-scripts')
    @if(config('scripts.footer'))
    {!! config('scripts.footer') !!}
    @endif
</body>

</html>