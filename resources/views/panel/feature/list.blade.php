@foreach ($features as $feat)
    <li class="list-group-item d-flex justify-content-between align-items-start">
        <div class="ms-2 me-auto">
            <div class="fw-bold">{{ $feat->name }}</div>
            {{ $feat->code }}
        </div>
        <div class="dropdown">
            <a href="javascript:void(0)" class="badge text-primary" type="button" data-bs-toggle="dropdown"
                id="opsi-{{ $feat->id }}">
                <i class="fa fa-ellipsis-h"></i>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item btn-edit" href="javascript:void(0)" data-feature="{{ json_encode($feat) }}"
                        id="btn-edit-{{ $feat->id }}">
                        <i class="fa fa-pen"></i>
                        Edit
                    </a>
                </li>
                <li>
                    <a class="dropdown-item text-danger btn-delete"
                        href="{{ route('feature.delete', ['id' => base64_encode($feat->id)]) }}"
                        data-feature="{{ json_encode($feat) }}" id="btn-delete-{{ $feat->id }}">
                        <i class="fa fa-trash-alt text-danger"></i>
                        Delete
                    </a>
                </li>
            </ul>
        </div>
    </li>
@endforeach
