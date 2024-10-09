{{-- Button Edit --}}
<a href="javascript:void(0)" data-project="{{ json_encode($dat) }}"
    class="btn btn-sm btn-warning text-center px-3 py-2 btn-edit" id="btn-edit-{{ $dat->id }}">
    <i class="fa fa-pen px-0"></i>
</a>

{{-- Button Delete --}}
<a href="{{ route('project.delete', ['id' => base64_encode($dat->id)]) }}"
    class="btn btn-sm btn-danger text-center px-3 py-2 btn-delete" id="btn-delete-{{ $dat->id }}">
    <i class="fa fa-trash-alt px-0"></i>
</a>
