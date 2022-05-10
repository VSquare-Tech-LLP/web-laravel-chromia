<nav class="navbar navbar-expand-md ">
    <div class="container position-relative">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="@lang('Toggle navigation')">
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
            <a href="{{route('frontend.index')}}" class="logo-wrapper mx-auto" aria-label="Logo">
                <img src="{{asset('storage/logos/'.config('site_logo'))}}" height="30px" width="175px" alt="{{appName()}} Logo"></a>
        @else
            <x-utils.link
                :href="route('frontend.index')"
                :text="appName()"
                class="navbar-brand mr-auto" />
        @endif
        <a class="nav-link search-link ml-auto"
           href="#searchForm"
           data-target="#searchForm"
           data-toggle="collapse"
           aria-label="Search Button">
            <svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1"
                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em"
                 height="1em">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M208 48c-88.366 0-160 71.634-160 160s71.634 160 160 160 160-71.634 160-160S296.366 48 208 48zM0 208C0 93.125 93.125 0 208 0s208 93.125 208 208c0 48.741-16.765 93.566-44.843 129.024l133.826 134.018c9.366 9.379 9.355 24.575-.025 33.941-9.379 9.366-24.575 9.355-33.941-.025L337.238 370.987C301.747 399.167 256.839 416 208 416 93.125 416 0 322.875 0 208z"></path>
            </svg>
        </a>



        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto mr-lg-4">
                @if($menus->count())
                    @foreach($menus->where('menu_id',1) as $menu)
                        @if($menu->id == $menu->parent_id)
                            @if($menu->getsons($menu->id)->count() > 1)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="{{ url(menuUrl($menu)) }}"
                                       id="navbarDropdownMenuLink"  aria-haspopup="true"
                                       aria-expanded="false">
                                        {{$menu->label}}
                                    </a>
                                    <ul class="dropdown-menu main" aria-labelledby="navbarDropdownMenuLink">
                                        @foreach($menu->getsons($menu->id) as $item)
                                            @if($menu->id != $item->id)
                                                @include('frontend.includes.partials.nav-dropdown', $item)
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a href="{{ url(menuUrl($menu)) }}"
                                       class="nav-link"
                                       id="menu-{{$menu->id}}">{{ $menu->label }}</a>
                                </li>
                            @endif
                        @endif
                    @endforeach
                @endif
            </ul>
        </div><!--navbar-collapse-->
        <ul class="navbar-nav">
            <li class="nav-item d-flex">
                <div class="collapse position-absolute w-100 px-2" id="searchForm">
                    <div class="d-flex search-wrapper shadow-sm align-items-center">
                        <input id="nav-search" type="text" class="form-control form-control-lg  border-0 flex-grow-1" name="q" placeholder="{{config('search_box_label','Search Here')}}" />
                        <a class="nav-link py-2"
                           href="#searchForm"
                           data-target="#searchForm"
                           data-toggle="collapse">
                            <svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em"
                                 height="1em">
                                <path
                                    d="M71.029 71.029c9.373-9.372 24.569-9.372 33.942 0L256 222.059l151.029-151.03c9.373-9.372 24.569-9.372 33.942 0 9.372 9.373 9.372 24.569 0 33.942L289.941 256l151.03 151.029c9.372 9.373 9.372 24.569 0 33.942-9.373 9.372-24.569 9.372-33.942 0L256 289.941l-151.029 151.03c-9.373 9.372-24.569 9.372-33.942 0-9.372-9.373-9.372-24.569 0-33.942L222.059 256 71.029 104.971c-9.372-9.373-9.372-24.569 0-33.942z"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </li>
        </ul>
    </div><!--container-->
</nav>

@push('after-scripts')
    <script>
        $('#nav-search').keydown(function(event) {
            // enter has keyCode = 13
            if (event.keyCode == 13) {
                var sstr = $('#nav-search').val().replace(/ /g, '+');
                window.location.href = "{{route('frontend.search')}}?q="+sstr;
                return false;
            }
        });
        $('#footer-search').keydown(function(event) {
            // enter has keyCode = 13
            if (event.keyCode == 13) {
                var sstr = $('#footer-search').val().replace(/ /g, '+');
                window.location.href = "{{route('frontend.search')}}?q="+sstr;
                return false;
            }
        });
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
