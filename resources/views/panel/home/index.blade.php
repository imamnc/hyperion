@extends('panel.layout')

@section('content')
    <div class="card border-0 h-md-100" data-theme="light"
        style="background: linear-gradient(112.14deg, #00D2FF 0%, #3A7BD5 100%); margin-top: -20px;">
        <div class="card-body">
            <div class="row align-items-center h-100">
                <div class="col-7 ps-xl-13">
                    <div class="text-white mb-6 pt-6">
                        <span class="fs-4 fw-semibold me-2 d-block lh-1 pb-2 opacity-75">Hi
                            {{ auth()->user()->name }},</span>
                        <span class="fs-2qx fw-bold">Selamat Datang</span>
                    </div>
                    <span class="fw-semibold text-white fs-6 mb-8 d-block opacity-75">
                        Saat ini anda telah login di panel admin Hyperion E-Procurement Aggregator
                    </span>
                    <div class="d-flex flex-column flex-sm-row d-grid gap-2">
                        <a href="{{ route('profile') }}" class="btn btn-success flex-shrink-0 me-2">Lihat Profile</a>
                        <a href="https://documenter.getpostman.com/view/6631471/2s8YRqkWU8"
                            class="btn btn-primary flex-shrink-0" style="background: rgba(255, 255, 255, 0.2)">
                            Baca Dokumentasi
                        </a>
                    </div>
                </div>
                <div class="col-5 pt-10">
                    <div class="bgi-no-repeat bgi-size-contain bgi-position-x-end h-225px"
                        style="background-image:url('/theme/dist/assets/media/svg/illustrations/easy/5.svg')"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('.dataTable').DataTable();
    </script>
@endpush
