@extends('panel.layout')

@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        {{-- Page Title --}}
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                My Account
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('home') }}" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">My Profile</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap mb-3">
                {{-- Image --}}
                <div class="me-7 mb-4">
                    <label for="profile_photo" class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative"
                        style="cursor: pointer;">
                        @if (auth()->user()->profile_photo_path)
                            <img src="{{ asset(auth()->user()->profile_photo_path) }}" alt="image">
                        @else
                            <img src="{{ asset('img/nophoto.jpg') }}" alt="image">
                        @endif
                        <div
                            class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                        </div>
                    </label>
                </div>
                {{-- Details --}}
                <div class="flex-grow-1">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <a href="javascript:void(0)"
                                    class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ auth()->user()->name }}</a>
                                <a href="javascript:void(0)">
                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z"
                                                fill="white"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-2 pe-2">
                                <a href="mailto:{{ auth()->user()->email }}"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    {{ auth()->user()->email }}
                                </a>
                            </div>
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-2 pe-2">
                                <a href="javascript:void(0)"
                                    class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                    <span class="svg-icon svg-icon-4 me-3">
                                        <i class="fa fa-clock"></i>
                                    </span>
                                    Since
                                    {{ date('d, M Y', strtotime(auth()->user()->created_at)) }}
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- Statistic --}}
                    <div class="d-flex flex-wrap flex-stack">
                        <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                            <!--begin::Nav item-->
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-3 @if (!session('tab')) active @endif"
                                    data-bs-toggle="tab" href="#edit_profile" dusk="btn-tab-edit-profile">
                                    Edit Profile
                                </a>
                            </li>
                            {{-- Ubah Password --}}
                            <li class="nav-item mt-2">
                                <a class="nav-link text-active-primary ms-0 me-10 py-3 @if (session('tab') == 'edit_password') show active @endif"
                                    data-bs-toggle="tab" href="#ubah_password" dusk="btn-tab-edit-password">
                                    Ubah Password
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade @if (!session('tab')) show active @endif" id="edit_profile"
                    role="tabpanel">
                    @include('panel.profile.tab_edit_profile')
                </div>
                <div class="tab-pane fade @if (session('tab') == 'edit_password') show active @endif" id="ubah_password"
                    role="tabpanel">
                    @include('panel.profile.tab_edit_password')
                </div>
            </div>
        </div>
    </div>
@endsection
