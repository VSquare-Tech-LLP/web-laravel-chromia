<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
    <div class="c-sidebar-brand d-lg-down-none">
        <svg class="c-sidebar-brand-full" width="118" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#full') }}"></use>
        </svg>
        <svg class="c-sidebar-brand-minimized" width="46" height="46" alt="CoreUI Logo">
            <use xlink:href="{{ asset('img/brand/coreui.svg#signet') }}"></use>
        </svg>
    </div><!--c-sidebar-brand-->

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <x-utils.link
                class="c-sidebar-nav-link"
                :href="route('admin.dashboard')"
                :active="activeClass(Route::is('admin.dashboard'), 'c-active')"
                icon="c-sidebar-nav-icon cil-speedometer"
                :text="__('Dashboard')" />
        </li>

        <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.posts.*') || Route::is('admin.categories.*') || Route::is('admin.tags.*'), 'c-open c-show') }}">
            <x-utils.link
                href="#"
                icon="c-sidebar-nav-icon cil-newspaper"
                class="c-sidebar-nav-dropdown-toggle"
                :text="__('Blog')"
                :permission="'blog_menu'"
            />

            <ul class="c-sidebar-nav-dropdown-items">
                @can('post_menu')
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.posts.create')"
                            icon="c-sidebar-nav-icon cil-pencil"
                            class="c-sidebar-nav-link"
                            :text="__('Write Post')"
                            :permission="'post_create'"/>
                    </li>
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.posts.index')"
                            icon="c-sidebar-nav-icon cil-list"
                            class="c-sidebar-nav-link {{ activeClass(Route::is('admin.posts.*'), 'c-active') }}"
                            :text="__('All Posts')"
                            :permission="'post_access'"/>
                    </li>
                @endcan

                @can('category_menu')
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.categories.index')"
                            icon="c-sidebar-nav-icon cil-stream"
                            class="c-sidebar-nav-link {{ activeClass(Route::is('admin.categories.*'), 'c-active') }}"
                            :text="__('Categories')"
                            :permission="'category_access'"/>
                    </li>
                @endcan

                @can('tag_menu')
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('admin.tags.index')"
                            icon="c-sidebar-nav-icon cil-tag"
                            class="c-sidebar-nav-link {{ activeClass(Route::is('admin.tags.*'), 'c-active') }}"
                            :text="__('Tags')"
                            :permission="'tag_access'"/>
                    </li>
                @endcan
            </ul>
        </li>

        @can('page_menu')
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link {{ activeClass(Route::is('admin.pages.*'), 'c-active') }}"
                    :href="route('admin.pages.index')"
                    :active="activeClass(Route::is('admin.pages.index'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-hamburger-menu"
                    :text="__('Pages')"/>
            </li>
        @endcan

        @can('file_manager_access')
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.file-manager')"
                    :active="activeClass(Route::is('admin.file-manager'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-folder"
                    :text="__('File Manager')" />
            </li>
        @endcan

        @can('redirects_menu')
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link {{ activeClass(Route::is('admin.redirects.*'), 'c-active') }}"
                    :href="route('admin.redirects.index')"
                    :active="activeClass(Route::is('admin.redirects.index'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-link"
                    :text="__('Redirects')"
                    :permission="'redirects_access'"/>
            </li>
        @endcan

        @can('form_access')
            <li class="c-sidebar-nav-item">
                <x-utils.link
                    class="c-sidebar-nav-link"
                    :href="route('admin.forms.index',['sorts' => ['id' => 'desc']])"
                    :active="activeClass(Route::is('admin.forms.index'), 'c-active')"
                    icon="c-sidebar-nav-icon cil-folder"
                    :text="__('Forms')" />
            </li>
        @endcan

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
            <li class="c-sidebar-nav-title">@lang('System')</li>

            <li class="c-sidebar-nav-dropdown {{ activeClass(Route::is('admin.auth.user.*') || Route::is('admin.auth.role.*'), 'c-open c-show') }}">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-user"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Access')" />

                <ul class="c-sidebar-nav-dropdown-items">
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
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.user.index')"
                                class="c-sidebar-nav-link"
                                :text="__('User Management')"
                                :active="activeClass(Route::is('admin.auth.user.*'), 'c-active')" />
                        </li>
                    @endif

                    @if ($logged_in_user->hasAllAccess())
                        <li class="c-sidebar-nav-item">
                            <x-utils.link
                                :href="route('admin.auth.role.index')"
                                class="c-sidebar-nav-link"
                                :text="__('Role Management')"
                                :active="activeClass(Route::is('admin.auth.role.*'), 'c-active')" />
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if ($logged_in_user->hasAllAccess())
            <li class="c-sidebar-nav-dropdown">
                <x-utils.link
                    href="#"
                    icon="c-sidebar-nav-icon cil-list"
                    class="c-sidebar-nav-dropdown-toggle"
                    :text="__('Logs')" />

                <ul class="c-sidebar-nav-dropdown-items">
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::dashboard')"
                            class="c-sidebar-nav-link"
                            :text="__('Dashboard')" />
                    </li>
                    <li class="c-sidebar-nav-item">
                        <x-utils.link
                            :href="route('log-viewer::logs.list')"
                            class="c-sidebar-nav-link"
                            :text="__('Logs')" />
                    </li>
                </ul>
            </li>
        @endif
    </ul>

    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div><!--sidebar-->
