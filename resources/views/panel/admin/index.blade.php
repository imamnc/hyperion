@extends('panel.layout')

@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        {{-- Page Title --}}
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                Kelola Admin
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('home') }}" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Admin</li>
            </ul>
        </div>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="container">
                <div class="row h-100 align-middle">
                    <div class="col-6 h-100 pt-5">
                        <a href="" id="btn-add-admin" class="btn btn-sm fw-bold btn-primary align-middle"
                            dusk="btn-create-admin">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                            </span>
                            Tambah Admin
                        </a>
                    </div>
                    <div class="col-6 h-100 pt-4 text-end">
                        <input type="search" id="dt-search" class="form-control d-inline" placeholder="Search"
                            style="width: 200px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-row-dashed" id="admin-table">
                <thead>
                    <tr>
                        <th><b>No</b></th>
                        <th><b>Nama</b></th>
                        <th><b>Email</b></th>
                        <th class="text-center"><b>Opsi</b></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('modal')
    {{-- Modal Create --}}
    <div class="modal fade" tabindex="-1" id="add-admin-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Admin</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-times"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.create') }}" method="post" autocomplete="off" id="form-add-admin">
                        @csrf
                        {{-- Nama --}}
                        <div class="form-group mb-4">
                            <label for="" class="mb-2"><b>Nama <span class="text-danger">*</span></b></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama">
                            <span class="invalid-feedback"></span>
                        </div>
                        {{-- Email --}}
                        <div class="form-group mb-4">
                            <label for="email" class="mb-2"><b>Email <span class="text-danger">*</span></b></label>
                            <input type="email" name="email" class="form-control"
                                placeholder="Contoh: example@mail.com">
                            <span class="invalid-feedback"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-add-admin" class="btn btn-primary"
                        dusk="submit-create-admin">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Edit --}}
    <div class="modal fade" tabindex="-1" id="edit-admin-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Admin</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-times"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.update') }}" method="post" autocomplete="off" id="form-edit-admin">
                        @csrf
                        {{-- ID --}}
                        <input type="hidden" name="id">
                        {{-- Nama --}}
                        <div class="form-group mb-4">
                            <label for="" class="mb-2"><b>Nama <span class="text-danger">*</span></b></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama">
                            <span class="invalid-feedback"></span>
                        </div>
                        {{-- Email --}}
                        <div class="form-group mb-4">
                            <label for="email" class="mb-2"><b>Email <span class="text-danger">*</span></b></label>
                            <input type="email" name="email" class="form-control"
                                placeholder="Contoh: example@mail.com">
                            <span class="invalid-feedback"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-edit-admin" class="btn btn-primary"
                        dusk="submit-edit-admin">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        // Init Datatable
        var admin_table = $('#admin-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
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
            admin_table.search($(this).val()).draw();
        });

        // Process Delete Admin
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            // Btn delete
            var btn_delete = $(this);
            // Show Alert
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Admin ?',
                text: 'Data admin akan dihapus secara permanen !',
                confirmButtonText: 'Delete',
                confirmButtonColor: '#009ef7',
                showCancelButton: true,
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Loader Button
                    btn_delete.html(`
                        <i class="fa fa-spinner fa-spin px-0"></i>
                    `).prop('disabled', true);
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
                            admin_table.ajax.reload();
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
                            btn_delete.html(`<i class="fa fa-trash-alt px-0"></i>`).prop(
                                'disabled', false);
                        }
                    });
                }
            });
        });

        // Trigger Modal Add Admin
        $(document).on('click', '#btn-add-admin', function(e) {
            e.preventDefault();
            // Reset form
            $('#form-add-admin').find('[type=email], [type=text]').removeClass('is-invalid');
            // Open modal
            $('#add-admin-modal').modal('show');
        });

        // Process Add Data Admin
        $(document).on('submit', '#form-add-admin', function(e) {
            e.preventDefault();
            // Loading button
            $('[form="form-add-admin"]').html(`
                <span><i class="fa fa-spinner fa-spin"></i></span>
                Loading...
            `).prop('disabled', true);
            // Ajax request
            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#add-admin-modal').modal('hide');
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
                        admin_table.ajax.reload();
                        // Reset button
                        $('[form="form-add-admin"]').html(`Simpan`).prop('disabled', false);
                        // Reset form
                        $('#form-add-admin').find('[type=email], [type=text]').val('').removeClass(
                            'is-invalid');
                    }
                },
                error: function(err) {
                    console.log(err);
                    // Validation error
                    if (err.status == 422) {
                        var error_messages = err.responseJSON.validation_error;
                        // Validate name
                        if (error_messages.name) {
                            $('#form-add-admin [name="name"]').addClass('is-invalid');
                            $('#form-add-admin [name="name"]').parent().find('.invalid-feedback').text(
                                error_messages.name[0]);
                        } else {
                            $('#form-add-admin [name="name"]').removeClass('is-invalid');
                        }
                        // Validate email
                        if (error_messages.email) {
                            $('#form-add-admin [name="email"]').addClass('is-invalid');
                            $('#form-add-admin [name="email"]').parent().find('.invalid-feedback').text(
                                error_messages.email[0]);
                        } else {
                            $('#form-add-admin [name="email"]').removeClass('is-invalid');
                        }
                        // Reset button
                        $('[form="form-add-admin"]').html(`Simpan`).prop('disabled', false);
                    } else {
                        // Toast error
                        Swal.fire({
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            title: `${err.responseJSON.message.substr(0, 100)}`,
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        });

        // Trigger Modal Edit Admin
        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            // Reset form
            $('#form-edit-admin').find('[type=email], [type=text]').removeClass('is-invalid');
            // Selected data
            var data = $(this).data('admin');
            // Set Value
            $('#form-edit-admin').find('[name="id"]').val(data.id);
            $('#form-edit-admin').find('[name="name"]').val(data.name);
            $('#form-edit-admin').find('[name="email"]').val(data.email);
            // Open modal
            $('#edit-admin-modal').modal('show');
        });

        // Process Edit Data Admin
        $(document).on('submit', '#form-edit-admin', function(e) {
            e.preventDefault();
            // Loading button
            $('[form="form-edit-admin"]').html(`
                <span><i class="fa fa-spinner fa-spin"></i></span>
                Loading...
            `).prop('disabled', true);
            // Ajax request
            $.ajax({
                type: 'post',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#edit-admin-modal').modal('hide');
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
                        admin_table.ajax.reload();
                        // Reset button
                        $('[form="form-edit-admin"]').html(`Simpan`).prop('disabled', false);
                        // Reset form
                        $('#form-edit-admin').find('[type=email], [type=text]').val('').removeClass(
                            'is-invalid');
                    }
                },
                error: function(err) {
                    console.log(err);
                    // Validation error
                    if (err.status == 422) {
                        var error_messages = err.responseJSON.validation_error;
                        // Validate name
                        if (error_messages.name) {
                            $('#form-edit-admin [name="name"]').addClass('is-invalid');
                            $('#form-edit-admin [name="name"]').parent().find('.invalid-feedback').text(
                                error_messages.name[0]);
                        } else {
                            $('#form-edit-admin [name="name"]').removeClass('is-invalid');
                        }
                        // Validate email
                        if (error_messages.email) {
                            $('#form-edit-admin [name="email"]').addClass('is-invalid');
                            $('#form-edit-admin [name="email"]').parent().find('.invalid-feedback')
                                .text(error_messages.email[0]);
                        } else {
                            $('#form-edit-admin [name="email"]').removeClass('is-invalid');
                        }
                        // Reset button
                        $('[form="form-edit-admin"]').html(`Simpan`).prop('disabled', false);
                    } else {
                        // Toast error
                        Swal.fire({
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            title: `${err.responseJSON.message.substr(0, 100)}`,
                            showConfirmButton: false,
                            showCancelButton: false,
                            timer: 2000
                        });
                    }
                }
            });
        });
    </script>
@endpush
