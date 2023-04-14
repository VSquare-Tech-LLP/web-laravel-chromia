<header class="header header-sticky {{ !env('APP_BACKEND_HEADER') ? 'd-flex d-lg-none': ''  }}">
    <div class="container-fluid">
        <button class="header-toggler px-md-0 me-md-3" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()">
            <i class="c-icon c-icon-lg cil-menu"></i>
        </button>
        <a class="header-brand d-md-none" href="#">
            <h2 class="c-sidebar-brand-full"> {{ env('APP_NAME') }}</h2>
        </a>
        <ul class="header-nav d-none d-md-flex">
            <li class="nav-item"><a class="nav-link" href="{{ route('frontend.index') }}">@lang('Home')</a></li>
            <li class="nav-item dropdown">
                <a class="nav-link py-0" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                </a>
                <x-utils.link :text="__(getLocaleName(app()->getLocale()))" id="navbarDropdownLanguageLink" class="nav-link dropdown-toggle" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" />

                @include('includes.partials.lang')
            </li>
        </ul>
        <ul class="header-nav ms-auto"></ul>

        <ul class="header-nav ms-auto me-4">
            <li class="nav-item dropdown">
                <x-utils.link class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                    <x-slot name="text">
                        <div class="avatar avatar-md">
                            <img class="avatar-img" src="{{ $logged_in_user->avatar }}" alt="{{ $logged_in_user->email ?? '' }}">
                        </div>
                    </x-slot>
                </x-utils.link>

                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="dropdown-header bg-light py-2">
                        <div class="fw-semibold">Account</div>
                    </div>

                    <x-utils.link class="dropdown-item" icon="icon me-2 cil-account-logout" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <x-slot name="text">
                            @lang('Logout')
                            <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                        </x-slot>
                    </x-utils.link>
                </div>
            </li>
        </ul>
    </div>
</header>