<form action="{{ route('user-profile-information.update') }}" method="POST" enctype="multipart/form-data"
    autocomplete="off" id="form-update-profile">
    @csrf
    @method('PUT')
    <input type="file" name="profile_photo" id="profile_photo" class="d-none" data-target=".profile-img">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group mb-3">
                <label class="mb-2" for="name"><b>Nama</b></label>
                <input type="text" name="name" id="name"
                    class="form-control @error('name') is-invalid @enderror" placeholder="Nama"
                    value="{{ old('name') ? old('name') : auth()->user()->name }}">
                @error('name')
                    <div class="invalid-feedback">
                        <i class="fa fa-exclamation-circle text-danger mr-1"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group mb-3">
                <label class="mb-2" for="email"><b>Alamat Email</b></label>
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                    value="{{ old('email') ? old('email') : auth()->user()->email }}">
                @error('email')
                    <div class="invalid-feedback">
                        <i class="fa fa-exclamation-circle text-danger mr-1"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-lg-12">
            <button type="submit" form="form-update-profile" class="btn btn-sm btn-primary mt-1"
                dusk="submit-edit-profile">
                <i class="fa fa-save me-1"></i>
                Simpan
            </button>
        </div>
    </div>
</form>

@push('modal')
    {{-- Modal Crop Profile Photo --}}
    <div class="modal fade" id="modal-crop" tabindex="-1" role="dialog" aria-labelledby="modal-crop-label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-crop-label">
                        SESUAIKAN FOTO PROFILE
                    </h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <span class="fa fa-times"></span>
                    </div>
                </div>
                <div class="modal-body py-1">
                    <div class="img-container">
                        <div class="row justify-content-center">
                            <div class="col-md-12 p-0" style="overflow: hidden;">
                                <img id="image" src="https://avatars0.githubusercontent.com/u/3456749" class="w-100">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Save</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('script')
    {{-- SUBMIT --}}
    <script>
        $(document).on('submit', '#form-update-profile', function(e) {
            // Loading button
            $('[form=form-update-profile]').html(`
                <i class="fa fa-spinner fa-spin me-1"></i>
                Loading...
            `).prop('disabled', true);
        });
    </script>
    {{-- CROP --}}
    <script>
        // Properti Crop
        var modal = $('#modal-crop');
        var image = document.getElementById('image');
        var cropper;

        // On change profile
        $('#profile_photo').on('change', function(e) {
            var reader, file, url;
            var files = e.target.files;
            var done = function(url) {
                image.src = url;
                modal.modal('show');
            };
            // Read image
            if (files && files.length > 0) {
                file = files[0];
                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        // On show hidden modal
        modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                dragMode: 'move',
                aspectRatio: 1
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        // Trigger croping
        $("#crop").click(function() {
            // Change button label
            $('#crop').text('Uploading...');
            $('#crop').prop('disabled', true);

            // Get cropped image
            canvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200,
            });

            // Convert to base64 data
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    // Base64 encoded image
                    var base64data = reader.result;
                    // Send image using ajax
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('profile.update_image') }}",
                        data: {
                            _token: $('[name="csrf-token"]').attr('content'),
                            image: base64data
                        },
                        success: function(data) {
                            modal.modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Foto profile disimpan',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 5000
                            });
                            setTimeout(() => {
                                document.location.reload();
                            }, 1200);
                        },
                        error: function(err) {
                            console.log(err.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Foto profile gagal disimpan',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 5000
                            });
                            setTimeout(() => {
                                document.location.reload();
                            }, 1200);
                        }
                    });
                }
            });
        })
    </script>
@endpush
