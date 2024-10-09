@extends('panel.layout')

@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        {{-- Page Title --}}
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                Kelola Project
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('home') }}" class="text-muted text-hover-primary">Home</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">Project</li>
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
                        <a href="" id="btn-add-project" class="btn btn-sm fw-bold btn-primary align-middle"
                            dusk="btn-create-project">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                            </span>
                            Tambah Project
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
            <table class="table table-row-dashed" id="project-table">
                <thead>
                    <tr>
                        <th><b>No</b></th>
                        <th><b>Nama</b></th>
                        <th><b>Code</b></th>
                        <th><b>Class</b></th>
                        <th><b>URL</b></th>
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
    <div class="modal fade" tabindex="-1" id="add-project-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Project</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-times"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('project.create') }}" method="post" autocomplete="off" id="form-add-project">
                        @csrf

                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#general-add">Data Project</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#menu-add">Kelola Menu</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="general-add" role="tabpanel">
                                {{-- Nama --}}
                                <div class="form-group mb-4">
                                    <label for="name-add" class="mb-2"><b>Nama <span
                                                class="text-danger">*</span></b></label>
                                    <input type="text" name="name" id="name-add" class="form-control"
                                        placeholder="Nama Project">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- Kode --}}
                                <div class="form-group mb-4">
                                    <label for="code-add" class="mb-2">
                                        <b>Kode <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="code" id="code-add" class="form-control"
                                        placeholder="Kode Project" onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- Class Project --}}
                                <div class="form-group mb-4">
                                    <label for="class-add" class="mb-2">
                                        <b>Class <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="class" id="class-add" class="form-control"
                                        placeholder="Contoh: KinoService" onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- URL Project --}}
                                <div class="form-group mb-4">
                                    <label for="url-add" class="mb-2">
                                        <b>URL <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="url" id="url-add" class="form-control"
                                        placeholder="Contoh: https://demo.itpi.co.id/api/public"
                                        onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- Key Project --}}
                                <div class="form-group mb-4">
                                    <label for="key-add" class="mb-2">
                                        <b>App Key <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="key" id="key-add" class="form-control"
                                        placeholder="App Key" onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="menu-add" role="tabpanel">
                                @foreach ($features as $feat)
                                    <div class="form-group mb-4">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="menus_add[]"
                                                value="{{ $feat->id }}">
                                            <span class="form-check-label"">
                                                {{ $feat->name }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-add-project" class="btn btn-primary"
                        dusk="submit-create-project">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Edit --}}
    <div class="modal fade" tabindex="-1" id="edit-project-modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Project</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-times"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('project.update') }}" method="post" autocomplete="off"
                        id="form-edit-project">
                        @csrf
                        {{-- ID --}}
                        <input type="hidden" name="id">

                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#general-edit">Data Project</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#menu-edit">Kelola Menu</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="general-edit" role="tabpanel">
                                {{-- Nama --}}
                                <div class="form-group mb-4">
                                    <label for="name-edit" class="mb-2"><b>Nama <span
                                                class="text-danger">*</span></b></label>
                                    <input type="text" name="name" id="name-edit" class="form-control"
                                        placeholder="Nama Project">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- Kode --}}
                                <div class="form-group mb-4">
                                    <label for="code-edit" class="mb-2">
                                        <b>Kode <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="code" id="code-edit" class="form-control"
                                        placeholder="Kode Project" onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- Class Project --}}
                                <div class="form-group mb-4">
                                    <label for="class-edit" class="mb-2">
                                        <b>Class <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="class" id="class-edit" class="form-control"
                                        placeholder="Contoh: KinoService" onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- URL Project --}}
                                <div class="form-group mb-4">
                                    <label for="url-edit" class="mb-2">
                                        <b>URL <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="url" id="url-edit" class="form-control"
                                        placeholder="Contoh: https://demo.itpi.co.id/api/public"
                                        onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                                {{-- Key Project --}}
                                <div class="form-group mb-4">
                                    <label for="key-edit" class="mb-2">
                                        <b>App Key <span class="text-danger">*</span></b>
                                    </label>
                                    <input type="text" name="key" id="key-edit" class="form-control"
                                        placeholder="App Key" onkeypress="preventSpace(event)">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="menu-edit" role="tabpanel">
                                @foreach ($features as $feat)
                                    <div class="form-group mb-4">
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="menus_edit[]"
                                                value="{{ $feat->id }}">
                                            <span class="form-check-label"">
                                                {{ $feat->name }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-edit-project" class="btn btn-primary"
                        dusk="submit-update-project">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        // Init Datatable
        var project_table = $('#project-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('project') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'class',
                    name: 'class'
                },
                {
                    data: 'url',
                    name: 'url'
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
            project_table.search($(this).val()).draw();
        });
        $(document).on('change', '#filter_project', function() {
            if ($(this).val() == 'all') {
                project_table.ajax.url(`{{ route('project') }}`).load();
            } else {
                project_table.ajax.url(`{{ route('project') }}?project_id=${$(this).val()}`).load();
            }
        });

        // Prevent Space
        function preventSpace(event) {
            if (event.which == 32) {
                event.preventDefault();
                return false;
            }
        }

        // Process Delete Admin
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            // Btn delete
            var btn_delete = $(this);
            // Show Alert
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Project ?',
                text: 'Data project akan dihapus secara permanen !',
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
                            project_table.ajax.reload();
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

        // Trigger Modal Add Admin
        $(document).on('click', '#btn-add-project', function(e) {
            e.preventDefault();
            // Reset tab
            document.querySelector(`#form-add-project [href="#general-add"]`).click();
            // Reset form
            $('#form-add-project').find('[type=email], [type=text]').removeClass('is-invalid');
            // Open modal
            $('#add-project-modal').modal('show');
        });

        // Process Add Data Admin
        $(document).on('submit', '#form-add-project', function(e) {
            e.preventDefault();
            // Loading button
            $('[form="form-add-project"]').html(`
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
                        $('#add-project-modal').modal('hide');
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
                        project_table.ajax.reload();
                        // Reset button
                        $('[form="form-add-project"]').html(`Simpan`).prop('disabled', false);
                        // Reset form
                        $('#form-add-project').find('[type=email], [type=text]').val('').removeClass(
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
                            $('#form-add-project [name="name"]').addClass('is-invalid');
                            $('#form-add-project [name="name"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.name[0]);
                        } else {
                            $('#form-add-project [name="name"]').removeClass('is-invalid');
                        }
                        // Validate code
                        if (error_messages.code) {
                            $('#form-add-project [name="code"]').addClass('is-invalid');
                            $('#form-add-project [name="code"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.code[0]);
                        } else {
                            $('#form-add-project [name="code"]').removeClass('is-invalid');
                        }
                        // Validate class
                        if (error_messages.class) {
                            $('#form-add-project [name="class"]').addClass('is-invalid');
                            $('#form-add-project [name="class"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.class[0]);
                        } else {
                            $('#form-add-project [name="class"]').removeClass('is-invalid');
                        }
                        // Validate url
                        if (error_messages.url) {
                            $('#form-add-project [name="url"]').addClass('is-invalid');
                            $('#form-add-project [name="url"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.url[0]);
                        } else {
                            $('#form-add-project [name="url"]').removeClass('is-invalid');
                        }
                        // Validate key
                        if (error_messages.key) {
                            $('#form-add-project [name="key"]').addClass('is-invalid');
                            $('#form-add-project [name="key"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.key[0]);
                        } else {
                            $('#form-add-project [name="key"]').removeClass('is-invalid');
                        }
                        // Reset button
                        $('[form="form-add-project"]').html(`Simpan`).prop('disabled', false);
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
                        // Reset button
                        $('[form="form-add-project"]').html(`Simpan`).prop('disabled', false);
                    }
                }
            });
        });

        // Trigger Modal Edit Admin
        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();
            // Reset tab
            document.querySelector(`#form-edit-project [href="#general-edit"]`).click();
            // Reset form
            $('#form-edit-project').find('[type=email], [type=text]').removeClass('is-invalid');
            // Selected data
            var data = $(this).data('project');
            // Set Value
            $('#form-edit-project').find('[name="id"]').val(data.id);
            $('#form-edit-project').find('[name="name"]').val(data.name);
            $('#form-edit-project').find('[name="code"]').val(data.code);
            $('#form-edit-project').find('[name="class"]').val(data.class);
            $('#form-edit-project').find('[name="url"]').val(data.url);
            $('#form-edit-project').find('[name="key"]').val(data.key);
            for (let i = 0; i < data.menus.length; i++) {
                const menu = data.menus[i];
                if (menu.pivot.flag_active == true) {
                    $(`[name='menus_edit[]'][value=${menu.id}]`).prop('checked', true);
                } else {
                    $(`[name='menus_edit[]'][value=${menu.id}]`).prop('checked', false);
                }
            }
            // Open modal
            $('#edit-project-modal').modal('show');
        });

        // Process Edit Data Admin
        $(document).on('submit', '#form-edit-project', function(e) {
            e.preventDefault();
            // Loading button
            $('[form="form-edit-project"]').html(`
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
                        $('#edit-project-modal').modal('hide');
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
                        project_table.ajax.reload();
                        // Reset button
                        $('[form="form-edit-project"]').html(`Simpan`).prop('disabled', false);
                        // Reset form
                        $('#form-edit-project').find('[type=email], [type=text]').val('').removeClass(
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
                            $('#form-edit-project [name="name"]').addClass('is-invalid');
                            $('#form-edit-project [name="name"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.name[0]);
                        } else {
                            $('#form-edit-project [name="name"]').removeClass('is-invalid');
                        }
                        // Validate code
                        if (error_messages.code) {
                            $('#form-edit-project [name="code"]').addClass('is-invalid');
                            $('#form-edit-project [name="code"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.code[0]);
                        } else {
                            $('#form-edit-project [name="code"]').removeClass('is-invalid');
                        }
                        // Validate class
                        if (error_messages.class) {
                            $('#form-edit-project [name="class"]').addClass('is-invalid');
                            $('#form-edit-project [name="class"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.class[0]);
                        } else {
                            $('#form-edit-project [name="class"]').removeClass('is-invalid');
                        }
                        // Validate url
                        if (error_messages.url) {
                            $('#form-edit-project [name="url"]').addClass('is-invalid');
                            $('#form-edit-project [name="url"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.url[0]);
                        } else {
                            $('#form-edit-project [name="url"]').removeClass('is-invalid');
                        }
                        // Validate key
                        if (error_messages.key) {
                            $('#form-edit-project [name="key"]').addClass('is-invalid');
                            $('#form-edit-project [name="key"]').parent().find('.invalid-feedback')
                                .text(
                                    error_messages.key[0]);
                        } else {
                            $('#form-edit-project [name="key"]').removeClass('is-invalid');
                        }
                        // Reset button
                        $('[form="form-edit-project"]').html(`Simpan`).prop('disabled', false);
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
                        // Reset form
                        $('#form-edit-project').find('[type=email], [type=text]').val('').removeClass(
                            'is-invalid');
                    }
                }
            });
        });
    </script>
@endpush
