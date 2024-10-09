@extends('auth.layout')

@section('content')
    <div class="d-flex flex-column flex-column-fluid flex-lg-row">

        {{-- Decoration --}}
        <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
            <div class="d-flex flex-center flex-lg-start flex-column">
                <a href="../../demo1/dist/index.html" class="mb-7">
                    <img alt="Logo" src="{{ asset('img/logo.svg') }}" style="width: 100%;">
                </a>
                <h2 class="text-dark fw-normal m-0">E-Procurement Indonesia Aggregator</h2>
            </div>
        </div>

        {{-- Login Form --}}
        <div class="d-flex flex-center w-lg-50 p-10">
            <div class="card rounded-3 w-md-550px">
                <div class="card-body p-10 p-lg-20">
                    <form action="{{ route('login') }}" method="POST" class="form w-100" autocomplete="off">
                        @csrf
                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                            <div class="text-gray-500 fw-semibold fs-6">Use your credentials to start your session</div>
                        </div>

                        <div class="row g-3 mb-9 d-none">
                            <div class="col-md-6">
                                <a href="#"
                                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                    <img alt="Logo" src="/theme/dist/assets/media/svg/brand-logos/google-icon.svg"
                                        class="h-15px me-3" />Sign in with Google</a>
                            </div>
                            <div class="col-md-6">
                                <a href="#"
                                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                    <img alt="Logo" src="/theme/dist/assets/media/svg/brand-logos/apple-black.svg"
                                        class="theme-light-show h-15px me-3" />
                                    <img alt="Logo" src="/theme/dist/assets/media/svg/brand-logos/apple-black-dark.svg"
                                        class="theme-dark-show h-15px me-3" />Sign in with Apple</a>
                            </div>
                        </div>
                        <div class="separator separator-content my-14 d-none">
                            <span class="w-125px text-gray-500 fw-semibold fs-7">Or with email</span>
                        </div>

                        <div class="fv-row mb-8">
                            <input type="email" name="email" id="email"
                                class="form-control bg-transparent @error('email') is-invalid @enderror" placeholder="Email"
                                value="{{ old('email') }}">
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="fv-row mb-3">
                            <input type="password" name="password" id="password"
                                class="form-control bg-transparent @error('password') is-invalid @enderror"
                                placeholder="Password" value="{{ old('password') }}">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (Route::has('password.request'))
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <div></div>
                                <a href="{{ route('password.request') }}" class="link-primary">Forgot Password ?</a>
                            </div>
                        @endif
                        <div class="d-grid mb-10">
                            <button type="submit" class="btn btn-primary" dusk="btn-login">
                                <span class="indicator-label">Sign In</span>
                            </button>
                        </div>
                        @if (Route::has('register'))
                            <div class="text-gray-500 text-center fw-semibold fs-6">
                                Not a Member yet?
                                <a href="{{ route('register') }}" class="link-primary">Sign up</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
