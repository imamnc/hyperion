@php
    $app_settings = \Itpi\Models\Settings::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    {{-- Metas --}}
    <meta charset="utf-8">
    <meta name="description" content="lorem">
    <meta name="keywords" content="Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti, veniam.">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta property="og:locale" content="{{ config('app.locale') }}">
    <meta property="og:title"
        content="{{ env('APP_NAME') }} | Lorem ipsum dolor sit amet consectetur adipisicing elit. Deleniti, veniam.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:site_name" content="{{ env('APP_NAME') }}">
    {{-- Title --}}
    <title>{{ env('APP_NAME') }}</title>
    {{-- Favicon --}}
    <link rel="shortcut icon" href="https://itpi.co.id/wp-content/uploads/2020/07/ITPI-THEME-THUMBNAIL.jpg"
        type="image/jpg">
    {{-- Styles --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8">
    <link href="/theme/dist/assets/plugins/global/plugins.bundle.css?version={{ $app_settings->assets_version }}"
        rel="stylesheet" type="text/css">
    <link href="/theme/dist/assets/css/style.bundle.css?version={{ $app_settings->assets_version }}" rel="stylesheet"
        type="text/css">
    <style>
        body {
            background-image: url('/theme/dist/assets/media/auth/bg6.jpg');
        }

        [data-theme="dark"] body {
            background-image: url('/theme/dist/assets/media/auth/bg6-dark.jpg');
        }

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
    </style>
    @stack('style')
</head>

<body id="kt_body" class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    {{-- CONFIG THEME MODE --}}
    <script>
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

    {{-- APP ROOT --}}
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        @yield('content')
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
        src="/theme/dist/assets/js/custom/authentication/sign-in/general.js?version={{ $app_settings->assets_version }}">
    </script>
    @stack('script')

</body>

</html>
