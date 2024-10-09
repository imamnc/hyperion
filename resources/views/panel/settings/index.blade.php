@extends('panel.layout')

@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        {{-- Page Title --}}
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                Settings
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('home') }}" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Settings</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-body">
                        <ul
                            class="nav nav-tabs nav-pills flex-row border-0 flex-md-column me-5 mb-3 mb-md-0 fs-6 min-w-lg-200px">
                            <li class="nav-item w-100 me-0 mb-md-2">
                                <a class="nav-link w-100 @if (!session('tab')) active @endif btn btn-flex btn-active-light-success"
                                    data-bs-toggle="tab" href="#general" dusk="btn-tab-general">
                                    <span class="svg-icon svg-icon-2">
                                        <i class="fa fa-cog"></i>
                                    </span>
                                    <span class="d-flex flex-column align-items-start">
                                        <span class="fs-4 fw-bold">General</span>
                                        <span class="fs-7">Pengaturan umum sistem</span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item w-100 me-0 mb-md-2">
                                <a class="nav-link w-100 @if (session('tab') == 'security') active @endif btn btn-flex btn-active-light-primary"
                                    data-bs-toggle="tab" href="#keamanan" dusk="btn-tab-security">
                                    <span class="svg-icon svg-icon-2">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                    <span class="d-flex flex-column align-items-start">
                                        <span class="fs-4 fw-bold">Keamanan</span>
                                        <span class="fs-7">Pengaturan PIN dan Password</span>
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item w-100">
                                <a class="nav-link w-100 @if (session('tab') == 'features') active @endif btn btn-flex btn-active-light-danger"
                                    data-bs-toggle="tab" href="#features" dusk="btn-tab-features">
                                    <span class="svg-icon svg-icon-2">
                                        <i class="fa fa-list"></i>
                                    </span>
                                    <span class="d-flex flex-column align-items-start">
                                        <span class="fs-4 fw-bold">Features</span>
                                        <span class="fs-7">Pengaturan master fitur servis</span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade @if (!session('tab')) show active @endif" id="general"
                        role="tabpanel">
                        @include('panel.settings.tab_general')
                    </div>
                    <div class="tab-pane fade @if (session('tab') == 'security') show active @endif" id="keamanan"
                        role="tabpanel">
                        @include('panel.settings.tab_security')
                    </div>
                    <div class="tab-pane fade @if (session('tab') == 'features') show active @endif" id="features"
                        role="tabpanel">
                        @include('panel.settings.tab_features')
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
