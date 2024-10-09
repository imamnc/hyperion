{{-- Button Reset PIN --}}
<a href="{{ route('user.reset_pin', ['id' => base64_encode($dat->id)]) }}"
    class="btn btn-sm btn-warning text-center px-3 py-2 btn-reset" id="btn-reset-{{ $dat->id }}">
    <i class="fa fa-key px-0"></i>
</a>

{{-- Button Delete --}}
<a href="{{ route('user.delete', ['id' => base64_encode($dat->id)]) }}"
    class="btn btn-sm btn-danger text-center px-3 py-2 btn-delete" id="btn-delete-{{ $dat->id }}">
    <i class="fa fa-trash-alt px-0"></i>
</a>
