<div class="card">
    <div class="card-header">
        <div class="container pt-5">
            <div class="row">
                <div class="col-sm-6">
                    <h5 class="card-title">
                        <i class="fa fa-list text-danger me-4"></i>
                        <span>Features Settings</span>
                    </h5>
                </div>
                <div class="col-sm-6 text-end">
                    <button class="btn btn-sm btn-primary" id="btn-add" dusk="btn-add-feature">
                        <i class="fa fa-plus-circle"></i>
                        Tambah Fitur
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <ul class="list-group" id="feature-list">
            {{-- Load from ajax --}}
        </ul>
    </div>
</div>

@push('modal')
    {{-- Modal Create --}}
    <div class="modal fade" tabindex="-1" id="add-feature-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Tambah Fitur</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-times"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('feature.create') }}" method="post" autocomplete="off" id="form-add-feature">
                        @csrf
                        {{-- Nama --}}
                        <div class="form-group mb-4">
                            <label for="" class="mb-2"><b>Nama <span class="text-danger">*</span></b></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama">
                            <span class="invalid-feedback"></span>
                        </div>
                        {{-- Kode --}}
                        <div class="form-group mb-4">
                            <label for="code" class="mb-2"><b>Kode <span class="text-danger">*</span></b></label>
                            <input type="text" name="code" class="form-control" placeholder="Kode Fitur"
                                onkeypress="prevent_space(event)">
                            <span class="invalid-feedback"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-add-feature" class="btn btn-primary"
                        dusk="submit-add-feature">Simpan</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Edit --}}
    <div class="modal fade" tabindex="-1" id="edit-feature-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Edit Fitur</h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-times"></span>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{ route('feature.update') }}" method="post" autocomplete="off" id="form-edit-feature">
                        @csrf
                        <input type="hidden" name="id" required>
                        {{-- Nama --}}
                        <div class="form-group mb-4">
                            <label for="" class="mb-2"><b>Nama <span class="text-danger">*</span></b></label>
                            <input type="text" name="name" class="form-control" placeholder="Nama">
                            <span class="invalid-feedback"></span>
                        </div>
                        {{-- Kode --}}
                        <div class="form-group mb-4">
                            <label for="code" class="mb-2"><b>Kode <span class="text-danger">*</span></b></label>
                            <input type="text" name="code" class="form-control" placeholder="Kode Fitur"
                                onkeypress="prevent_space(event)">
                            <span class="invalid-feedback"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" form="form-edit-feature" class="btn btn-primary"
                        dusk="submit-edit-feature">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        // Function load feature
        function load_feature() {
            // Loader indicator
            $('#feature-list').html(`
                <div class="text-center text-primary" id="feature-loader">
                    <i class="fa fa-spinner fa-spin text-primary me-1"></i>
                    Memuat
                </div>
            `);
            // Get data from server
            $.get(`{{ route('feature') }}`, function(data) {
                $('#feature-list').html(data);
            });
        }

        // Prevent Space
        function prevent_space(event) {
            if (event.which == 32) {
                event.preventDefault();
                return false;
            }
        }

        // Load data
        $(document).ready(function() {
            load_feature();
        });

        // Trigger add
        $(document).on('click', '#btn-add', function() {
            // Show modal
            $('#add-feature-modal').modal('show');
            // Reset
            $('#form-add-feature').find('[type=text]').removeClass('is-invalid').val('');
        });

        // Process add
        $(document).on('submit', '#form-add-feature', function(e) {
            e.preventDefault();
            // Loading button
            $('[form="form-add-feature"]').html(`
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
                        $('#add-feature-modal').modal('hide');
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
                        load_feature();
                        // Reset button
                        $('[form="form-add-feature"]').html(`Simpan`).prop('disabled', false);
                        // Reset form
                        $('#form-add-feature').find('[type=text]').val('').removeClass(
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
                            $('#form-add-feature [name="name"]').addClass('is-invalid');
                            $('#form-add-feature [name="name"]').parent().find('.invalid-feedback')
                                .text(error_messages.name[0]);
                        } else {
                            $('#form-add-feature [name="name"]').removeClass('is-invalid');
                        }
                        // Validate code
                        if (error_messages.code) {
                            $('#form-add-feature [name="code"]').addClass('is-invalid');
                            $('#form-add-feature [name="code"]').parent().find('.invalid-feedback')
                                .text(error_messages.code[0]);
                        } else {
                            $('#form-add-feature [name="code"]').removeClass('is-invalid');
                        }
                        // Reset button
                        $('[form="form-add-feature"]').html(`Simpan`).prop('disabled', false);
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
            $('#form-edit-feature').find('[type=text]').removeClass('is-invalid');
            // Selected data
            var data = $(this).data('feature');
            // Set Value
            $('#form-edit-feature').find('[name="id"]').val(data.id);
            $('#form-edit-feature').find('[name="name"]').val(data.name);
            $('#form-edit-feature').find('[name="code"]').val(data.code);
            // Open modal
            $('#edit-feature-modal').modal('show');
        });

        // Process edit
        $(document).on('submit', '#form-edit-feature', function(e) {
            e.preventDefault();
            // Loading button
            $('[form="form-edit-feature"]').html(`
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
                        $('#edit-feature-modal').modal('hide');
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
                        load_feature();
                        // Reset button
                        $('[form="form-edit-feature"]').html(`Simpan`).prop('disabled', false);
                        // Reset form
                        $('#form-edit-feature').find('[type=text]').val('').removeClass('is-invalid');
                    }
                },
                error: function(err) {
                    console.log(err);
                    // Validation error
                    if (err.status == 422) {
                        var error_messages = err.responseJSON.validation_error;
                        // Validate name
                        if (error_messages.name) {
                            $('#form-edit-feature [name="name"]').addClass('is-invalid');
                            $('#form-edit-feature [name="name"]').parent().find('.invalid-feedback')
                                .text(error_messages.name[0]);
                        } else {
                            $('#form-edit-feature [name="name"]').removeClass('is-invalid');
                        }
                        // Validate code
                        if (error_messages.code) {
                            $('#form-edit-feature [name="code"]').addClass('is-invalid');
                            $('#form-edit-feature [name="code"]').parent().find('.invalid-feedback')
                                .text(error_messages.code[0]);
                        } else {
                            $('#form-edit-feature [name="code"]').removeClass('is-invalid');
                        }
                        // Reset button
                        $('[form="form-edit-feature"]').html(`Simpan`).prop('disabled', false);
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

        // Process Delete Admin
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            // Btn delete
            var btn_delete = $(this);
            // Show Alert
            Swal.fire({
                icon: 'warning',
                title: 'Hapus Fitur ?',
                text: 'Data fitr akan dihapus secara permanen !',
                confirmButtonText: 'Delete',
                confirmButtonColor: '#009ef7',
                showCancelButton: true,
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
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
                            load_feature();
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
                        }
                    });
                }
            });
        });
    </script>
@endpush
