<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fa fa-lock text-primary me-4"></i>
            <span>Security Settings</span>
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('settings.save') }}" method="post">
            @csrf
            <input type="hidden" name="action" value="security">
            {{-- Password Default --}}
            <div class="form-group mb-3">
                <label for="password_default" class="mb-2 ps-1"><b>Password Default</b></label>
                <input type="text" name="password_default" id="password_default"
                    class="form-control @error('password_default') is-invalid @endif"
                    value="{{ $settings->password_default }}">
                @error('password_default')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            {{-- PIN Default --}}
            <div class="form-group
                    mb-3">
                <label for="pin_default" class="mb-2 ps-1"><b>PIN Default</b></label>
                <input type="text" name="pin_default" id="pin_default"
                    class="form-control @error('pin_default') is-invalid @endif" value="{{ @old('pin_default') ? @old('pin_default') : $settings->pin_default }}">
                @error('pin_default')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
                    text-end my-4">
                <button type="submit" class="btn btn-sm btn-primary" dusk="submit-security-settings">
                    <i class="far fa-save mr-3"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
