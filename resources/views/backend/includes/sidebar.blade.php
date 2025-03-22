<div class="sidebar sidebar-dark sidebar-fixed" id="sidebar">
    <div class="sidebar-brand d-none d-md-flex">
        {{-- <svg class="sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">--}}
        {{-- <use xlink:href="{{ asset('img/brand/coreui.svg#full') }}"></use>--}}
        {{-- </svg>--}}
        {{-- <svg class="sidebar-brand-full" width="46" height="46" alt="CoreUI Logo">--}}
        {{-- <use xlink:href="{{ asset('img/brand/coreui.svg#signet') }}"></use>--}}
        {{-- </svg>--}}
        <h2 class="sidebar-brand-full"> {{ env('APP_NAME') }}</h2>
    </div><!--brand-->

    <ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
        <li class="nav-item">
            <x-utils.link class="nav-link" :href="route('admin.dashboard')" :active="activeClass(Route::is('admin.dashboard'), 'c-active')" icon="nav-icon cil-speedometer" :text="__('Dashboard')" />
        </li>


        @if (
        $logged_in_user->hasAllAccess() ||
        (
        $logged_in_user->can('admin.access.user.list') ||
        $logged_in_user->can('admin.access.user.deactivate') ||
        $logged_in_user->can('admin.access.user.reactivate') ||
        $logged_in_user->can('admin.access.user.clear-session') ||
        $logged_in_user->can('admin.access.user.impersonate') ||
        $logged_in_user->can('admin.access.user.change-password')
        )
        )
        <li class="nav-title">@lang('Face Swap')</li>
        
        <li class="nav-item">
            <x-utils.link :href="route('admin.swaplogs')" class="nav-link" :text="__('Swap Log')"
                :active="activeClass(Route::is('admin.swaplogs'), 'active')" />
        </li>
        <li class="nav-title">@lang('System')</li>

        <li class="nav-group {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'open show') }}">
            <x-utils.link href="#" icon="nav-icon cil-user" class="nav-link nav-group-toggle" :text="__('Access')" />

            <ul class="nav-group-items">
                @if (
                $logged_in_user->hasAllAccess() ||
                (
                $logged_in_user->can('admin.access.user.list') ||
                $logged_in_user->can('admin.access.user.deactivate') ||
                $logged_in_user->can('admin.access.user.reactivate') ||
                $logged_in_user->can('admin.access.user.clear-session') ||
                $logged_in_user->can('admin.access.user.impersonate') ||
                $logged_in_user->can('admin.access.user.change-password')
                )
                )
                <li class="nav-item">
                    <x-utils.link :href="route('admin.auth.user.index')" class="nav-link" :text="__('User Management')" :active="activeClass(Route::is('admin.auth.user.*'), 'active')" />
                </li>
                <li class="nav-item">
                    <x-utils.link :href="route('admin.auth.user.deactivated')" class="nav-link" :text="__('Deactivated Users')" permission="admin.access.user.reactivate" :active="activeClass(Route::is('admin.auth.user.deactivated'), 'active')" />
                </li>
                @if ($logged_in_user->hasAllAccess())
                <li class="nav-item">
                    <x-utils.link :href="route('admin.auth.user.deleted')" class="nav-link" :text="__('Deleted Users')" :active="activeClass(Route::is('admin.auth.user.deleted'), 'active')" />
                </li>
                @endif
                @endif

                @if ($logged_in_user->hasAllAccess())
                <li class="nav-item">
                    <x-utils.link :href="route('admin.auth.role.index')" class="nav-link" :text="__('Role Management')" :active="activeClass(Route::is('admin.auth.role.*'), 'active')" />
                </li>
                @endif
            </ul>
        </li>
        @endif

        @if ($logged_in_user->hasAllAccess())
        <li class="nav-group">
            <x-utils.link href="#" icon="nav-icon cil-list" class="nav-link nav-group-toggle" :text="__('Logs')" />

            <ul class="nav-group-items">
                <li class="nav-item">
                    <x-utils.link :href="route('log-viewer::dashboard')" class="nav-link" :text="__('Dashboard')" />
                </li>
                <li class="nav-item">
                    <x-utils.link :href="route('log-viewer::logs.list')" class="nav-link" :text="__('Logs')" />
                </li>
            </ul>
        </li>
        @endif

        @if ($logged_in_user->hasAllAccess())
        <li class="nav-group">
            <x-utils.link href="#" icon="nav-icon cil-list" class="nav-link nav-group-toggle" :text="__('Templates')" />
        
            <ul class="nav-group-items">
                <li class="nav-item">
                    <x-utils.link :href="route('admin.categories.index')" class="nav-link" :text="__('Categories')" />
                </li>
                <li class="nav-item">
                    <x-utils.link :href="route('log-viewer::logs.list')" class="nav-link" :text="__('Packs')" />
                </li>
                <li class="nav-item">
                    <x-utils.link :href="route('admin.images.index')" class="nav-link" :text="__('Images')" />
                </li>
            </ul>
        </li>
        @endif

        @can('robots_text_menu')
        <li class="nav-item">
            <x-utils.link class="nav-link" :href="route('admin.robots_file_read')" :active="activeClass(Route::is('admin.robots_file_read'), 'c-active')" icon="nav-icon cil-file" :text="__('Robots File')" />
        </li>
        @endcan

        <li class="nav-title">@lang('Logout')</li>

        <li class="nav-item">
            <x-utils.link class="nav-link" icon="c-icon cil-account-logout me-2" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <x-slot name="text">
                    Logout
                    <x-forms.post :action="route('frontend.auth.logout')" id="logout-form" class="d-none" />
                </x-slot>
            </x-utils.link>
        </li>
    </ul>

    <button class="sidebar-toggler" type="button" data-coreui-toggle="unfoldable"></button>
</div><!--sidebar-->
