@extends('panel.layout')

@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        {{-- Page Title --}}
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                Kelola User
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('home') }}" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">User</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="container">
                <div class="row h-100 align-middle">
                    <div class="col-6 h-100 pt-4">
                        <select name="project_id" id="filter_project" class="form-select w-250px">
                            <option value="all">Semua Service</option>
                            @foreach ($projects as $pro)
                                <option value="{{ $pro->id }}">{{ $pro->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 h-100 pt-4 text-end">
                        <input type="search" id="dt-search" class="form-control d-inline" placeholder="Search"
                            style="width: 200px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-row-dashed" id="user-table">
                <thead>
                    <tr>
                        <th><b>No</b></th>
                        <th><b>Nama</b></th>
                        <th><b>Project</b></th>
                        <th><b>Email/Username</b></th>
                        <th class="text-center"><b>Opsi</b></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script>
        // Init Datatable
        var user_table = $('#user-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('user') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'project',
                    name: 'project'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'option',
                    name: 'option',
                    orderable: false,
                    searchable: false,
                    class: 'text-center'
                },
            ]
        });
        $(document).on('keyup', '#dt-search', function() {
            user_table.search($(this).val()).draw();
        });
        $(document).on('change', '#filter_project', function() {
            if ($(this).val() == 'all') {
                user_table.ajax.url(`{{ route('user') }}`).load();
            } else {
                user_table.ajax.url(`{{ route('user') }}?project_id=${$(this).val()}`).load();
            }
        });

        // Process Reset PIN
        $(document).on('click', '.btn-reset', function(e) {
            e.preventDefault();
            // Btn reset
            var btn_reset = $(this);
            // Show Alert
            Swal.fire({
                icon: 'warning',
                title: 'Reset PIN ?',
                text: 'PIN dari user akan direset ke value default !',
                confirmButtonText: 'Reset',
                confirmButtonColor: '#009ef7',
                showCancelButton: true,
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Loader Button
                    btn_reset.html(`
                        <i class="fa fa-spinner fa-spin px-0"></i>
                    `).addClass('disabled');
                    // Ajax request
                    $.ajax({
                        type: 'get',
                        url: btn_reset.attr('href'),
                        success: function(response) {
                            // Toast success
                            Swal.fire({
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 2000
                            });
                            // Reload datatable
                            user_table.ajax.reload();
                        },
                        error: function(err) {
                            console.log(err);
                            // Toast success
                            Swal.fire({
                                icon: 'error',
                                toast: true,
                                position: 'top-end',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 2000
                            });
                            // Reset button
                            btn_reset.html(`<i class="fa fa-key px-0"></i>`).removeClass(
                                'disabled');
                        }
                    });
                }
            });
        });

        // Process Delete Admin
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            // Btn delete
            var btn_delete = $(this);
            // Show Alert
            Swal.fire({
                icon: 'warning',
                title: 'Hapus User ?',
                text: 'Data user akan dihapus secara permanen !',
                confirmButtonText: 'Delete',
                confirmButtonColor: '#009ef7',
                showCancelButton: true,
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Loader Button
                    btn_delete.html(`
                        <i class="fa fa-spinner fa-spin px-0"></i>
                    `).addClass('disabled');
                    // Ajax request
                    $.ajax({
                        type: 'get',
                        url: btn_delete.attr('href'),
                        success: function(response) {
                            // Toast success
                            Swal.fire({
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 2000
                            });
                            // Reload datatable
                            user_table.ajax.reload();
                        },
                        error: function(err) {
                            console.log(err);
                            // Toast success
                            Swal.fire({
                                icon: 'error',
                                toast: true,
                                position: 'top-end',
                                title: `${response.message}`,
                                showConfirmButton: false,
                                showCancelButton: false,
                                timer: 2000
                            });
                            // Reset button
                            btn_delete.html(`<i class="fa fa-trash-alt px-0"></i>`).removeClass(
                                'disabled');
                        }
                    });
                }
            });
        });
    </script>
@endpush
