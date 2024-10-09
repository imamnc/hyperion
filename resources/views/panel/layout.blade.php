@php
    $app_settings = \Itpi\Models\Settings::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    {{-- METAS --}}
    <meta charset="utf-8">
    <meta name="description" content="lorem">
    <meta name="keywords" content="Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti, veniam.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:locale" content="{{ config('app.locale') }}">
    <meta property="og:title"
        content="{{ env('APP_NAME') }} | Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti, veniam.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="{{ env('APP_NAME') }}">
    {{-- ICONS --}}
    <link rel="shortcut icon" href="https://itpi.co.id/wp-content/uploads/2020/07/ITPI-THEME-THUMBNAIL.jpg"
        type="image/jpg">
    {{-- TITLE --}}
    <title>{{ env('APP_NAME') }}</title>
    {{-- STYLE --}}
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
    <link
        href="/theme/dist/assets/plugins/custom/datatables/datatables.bundle.css?version={{ $app_settings->assets_version }}"
        rel="stylesheet" type="text/css">
    <link href="/theme/dist/assets/plugins/global/plugins.bundle.css?version={{ $app_settings->assets_version }}"
        rel="stylesheet" type="text/css">
    <link href="/theme/dist/assets/css/style.bundle.css?version={{ $app_settings->assets_version }}" rel="stylesheet"
        type="text/css">
    <link rel="stylesheet"
        href="/theme/dist/assets/plugins/custom/cropper/cropper.css?version={{ $app_settings->assets_version }}">
    <style>
        /* width */
        ::-webkit-scrollbar {
            width: 5px;
            height: 3px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .preloader {
            position: fixed;
            z-index: 10000;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: #fff;
            text-align: center;
            padding-top: 43vh;
        }

        .loader {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .loader div {
            display: inline-block;
            position: absolute;
            left: 8px;
            width: 16px;
            background: #0095e8;
            animation: loader 1.2s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        }

        .loader div:nth-child(1) {
            left: 8px;
            animation-delay: -0.24s;
        }

        .loader div:nth-child(2) {
            left: 32px;
            animation-delay: -0.12s;
        }

        .loader div:nth-child(3) {
            left: 56px;
            animation-delay: 0;
        }

        @keyframes loader {
            0% {
                top: 8px;
                height: 64px;
            }

            50%,
            100% {
                top: 24px;
                height: 32px;
            }
        }
    </style>
    @stack('style')
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

    {{-- PRELOADER --}}
    <div class="preloader">
        <div class="loader">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    {{-- APP ROOT --}}
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            {{-- HEADER --}}
            @include('panel.components.header')

            {{-- WRAPPER --}}
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
                {{-- SIDEBAR --}}
                @include('panel.components.sidebar')

                {{-- MAIN WRAPPER --}}
                <div class="app-main flex-column flex-row-fluid mt-4" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        {{-- TOOLBAR --}}
                        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                            @yield('toolbar')
                        </div>
                        {{-- CONTENT --}}
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                    {{-- FOOTER --}}
                    @include('panel.components.footer')
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    @stack('modal')

    {{-- SCRIPT --}}
    <script>
        var hostUrl = "/theme/dist/assets/";
    </script>
    <script src="/theme/dist/assets/plugins/global/plugins.bundle.js?version={{ $app_settings->assets_version }}"></script>
    <script src="/theme/dist/assets/js/scripts.bundle.js?version={{ $app_settings->assets_version }}"></script>
    <script
        src="/theme/dist/assets/plugins/custom/datatables/datatables.bundle.js?version={{ $app_settings->assets_version }}">
    </script>
    <script src="/theme/dist/assets/plugins/custom/cropper/cropper.js?version={{ $app_settings->assets_version }}">
    </script>
    <script
        src="/theme/dist/assets/plugins/custom/compressorjs/compressor.min.js?version={{ $app_settings->assets_version }}">
    </script>
    <script>
        // Preloader
        $(document).ready(function() {
            setTimeout(() => {
                $('.preloader').slideUp('fast');
            }, 500);
        });

        // CONFIG APP MODE
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-theme-mode");
            } else {
                if (localStorage.getItem("data-theme") !== null) {
                    themeMode = localStorage.getItem("data-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-theme", themeMode);
        }
    </script>
    @stack('script')

    {{-- TOAST --}}
    @if (session('toast-success'))
        <script type='text/javascript'>
            Swal.fire({
                icon: 'success',
                title: `{{ session('toast-success') }}`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 5000
            });
        </script>
    @endif
    @if (session('toast-error'))
        <script type='text/javascript'>
            Swal.fire({
                icon: 'error',
                title: `{{ session('toast-error') }}`,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 5000
            });
        </script>
    @endif
</body>

</html>
