<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    {{-- TITLE --}}
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ route('home') }}">
            <img alt="Logo" src="{{ asset('img/logo.svg') }}" class="w-100px app-sidebar-logo-default">
            <img alt="Logo" src="{{ asset('img/logo.svg') }}" class="w-80px app-sidebar-logo-minimize">
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor" />
                </svg>
            </span>
        </div>
    </div>

    {{-- MENU --}}
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">

                {{-- MENU HOME --}}
                <div class="menu-item">
                    <a class="menu-link @if (request()->routeIs('home*')) active @endif" href="{{ route('home') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa fa-home"></i>
                            </span>
                        </span>
                        <span class="menu-title">Home</span>
                    </a>
                </div>

                {{-- DIVIDER TRANSAKSI --}}
                <div class="menu-item pt-3">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Transaksi</span>
                    </div>
                </div>

                {{-- MENU MANAGE PROJECT --}}
                <div class="menu-item">
                    <a class="menu-link @if (request()->routeIs('project*')) active @endif" href="{{ route('project') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa fa-project-diagram"></i>
                            </span>
                        </span>
                        <span class="menu-title">Kelola Project</span>
                    </a>
                </div>

                {{-- MENU USER --}}
                <div class="menu-item">
                    <a class="menu-link @if (request()->routeIs('user*')) active @endif" href="{{ route('user') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa fa-user-group"></i>
                            </span>
                        </span>
                        <span class="menu-title">Kelola User</span>
                    </a>
                </div>

                {{-- MENU ADMIN --}}
                <div class="menu-item">
                    <a class="menu-link @if (request()->routeIs('admin*')) active @endif" href="{{ route('admin') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa fa-user-shield"></i>
                            </span>
                        </span>
                        <span class="menu-title">Kelola Admin</span>
                    </a>
                </div>

                {{-- CONTOH DROPDOWN MENU --}}
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion d-none">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa fa-comments"></i>
                            </span>
                        </span>
                        <span class="menu-title">Dropdown Menu</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        {{-- Sub Menu 1 --}}
                        <div class="menu-item">
                            <a href="" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Sub Menu 1</span>
                            </a>
                        </div>
                        {{-- Sub Menu 2 --}}
                        <div class="menu-item">
                            <a href="" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Sub Menu 2</span>
                            </a>
                        </div>
                        {{-- Sub Menu 3 --}}
                        <div class="menu-item">
                            <a href="" class="menu-link">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Sub Menu 3</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- DIVIDER SETTINGS --}}
                <div class="menu-item pt-3">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Settings</span>
                    </div>
                </div>

                {{-- MENU ADMIN --}}
                <div class="menu-item">
                    <a class="menu-link @if (request()->routeIs('settings*')) active @endif"
                        href="{{ route('settings') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <i class="fa fa-cog"></i>
                            </span>
                        </span>
                        <span class="menu-title">Settings</span>
                    </a>
                </div>

            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <a href="https://documenter.getpostman.com/view/6631471/2s8YRqkWU8"
            class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100">
            <span class="btn-label">DOKUMENTASI</span>
        </a>
    </div>
    <!--end::Footer-->
</div>
