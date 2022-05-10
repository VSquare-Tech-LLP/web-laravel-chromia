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
            <div class="col-lg-4 mb-3 col-12 position-relative">
                <input type="text" class="form-control" id="footer-search" name="q"
                       placeholder="{{config('search_box_label','Search Here')}}">
                <div class="search-icon">
                    <svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1"
                         xmlns="http://www.w3.org/2000/svg" width="1em"
                         height="1em">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M208 48c-88.366 0-160 71.634-160 160s71.634 160 160 160 160-71.634 160-160S296.366 48 208 48zM0 208C0 93.125 93.125 0 208 0s208 93.125 208 208c0 48.741-16.765 93.566-44.843 129.024l133.826 134.018c9.366 9.379 9.355 24.575-.025 33.941-9.379 9.366-24.575 9.355-33.941-.025L337.238 370.987C301.747 399.167 256.839 416 208 416 93.125 416 0 322.875 0 208z"></path>
                    </svg>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="footer-menu">
                    <div class="menu-title">{{config('footer.about_section_title') ?? 'About Us'}}</div>
                    <p>{{config('app.about') ?? ""}}</p>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="footer-menu">
                    <div
                        class="menu-title">{{config('footer.latest_post_section_title') ?? 'Recent Posts' }}</div>
                    <ul class="nav">
                        @foreach($footer_recent_posts as $post)
                            <li class="nav-item">
                                <a href="{{ route('frontend.single-post',['slug'=>$post->slug]) }}"
                                   class="nav-link"
                                   id="recent-menu-{{$post->id}}">{{ $post->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                @if($menus->count())
                    <div class="footer-menu">
                        <div class="menu-title"> {{config('footer.important_links_section_title') ?? 'Important Links'}}</div>
                        <ul class="nav">
                            @foreach($menus->where('menu_id',2) as $menu)
                                <li class="nav-item">
                                    <a href="{{ url(menuUrl($menu)) }}"
                                       class="nav-link"
                                       id="footer-menu-{{$menu->id}}">{{ $menu->label }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else

                @endif
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
