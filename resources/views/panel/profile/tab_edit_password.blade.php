<form action="{{ route('user-password.update') }}" method="POST" autocomplete="off" id="form-update-password">
    @csrf
    @method('PUT')
    <div class="row">
        @if (!empty(auth()->user()->password))
            <div class="col-lg-12">
                <div class="form-group mb-3">
                    <label class="mb-2" for="current_password">
                        <b>Kata Sandi Saat Ini</b>
                    </label>
                    <input type="password" name="current_password" id="current_password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        placeholder="Kata Sandi Saat Ini">
                    @error('current_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        @endif
        <div class="col-lg-12">
            <div class="form-group mb-3">
                <label class="mb-2" for="password">
                    <b>Kata Sandi Baru</b>
                </label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Kata Sandi Baru">
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group mb-3">
                <label class="mb-2" for="password_confirmation">
                    <b>Konfirmasi Kata Sandi</b>
                </label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    placeholder="Konfirmasi Kata Sandi">
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-lg-12">
            <button type="submit" form="form-update-password" class="btn btn-sm btn-primary mt-1"
                dusk="submit-edit-password">
                <i class="fa fa-save me-1"></i>
                Simpan
            </button>
        </div>
    </div>
</form>

@push('script')
    <script>
        $(document).on('submit', '#form-update-password', function(e) {
            // Loading button
            $('[form=form-update-password]').html(`
                <i class="fa fa-spinner fa-spin me-1"></i>
                Loading...
            `).prop('disabled', true);
        });
    </script>
@endpush
