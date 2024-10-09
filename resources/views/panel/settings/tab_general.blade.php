<div class="card">
    <div class="card-header">
        <h5 class="card-title">
            <i class="fa fa-cog text-success me-4"></i>
            <span>General Settings</span>
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('settings.save') }}" method="post">
            @csrf
            <input type="hidden" name="action" value="general">
            {{-- App Version --}}
            <div class="form-group mb-3">
                <label for="app_version" class="mb-2 ps-1"><b>App Version</b></label>
                <input type="text" name="app_version" id="app_version"
                    class="form-control @error('app_version') is-invalid @endif" value="{{ $settings->app_version }}">
                @error('app_version')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            {{-- Assets Version --}}
            <div class="form-group
                    mb-3">
                <label for="assets_version" class="mb-2 ps-1"><b>Assets Version</b></label>
                <input type="text" name="assets_version" id="assets_version"
                    class="form-control @error('assets_version') is-invalid @endif"
                    value="{{ $settings->assets_version }}">
                @error('assets_version')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group
                    text-end my-4">
                <button type="submit" class="btn btn-sm btn-primary" dusk="submit-general-settings">
                    <i class="far fa-save mr-3"></i>
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
