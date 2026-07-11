@php
    $editUrl = route('regions.edit', $row->id);
    $deleteUrl = route('regions.destroy', $row->id);
@endphp
<div class="dropdown">
    <button class="btn btn-sm btn-default" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="icon-base ti tabler-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu">
        <li>
            <a href="{{ $editUrl }}" class="dropdown-item">
                <i class="icon-base ti tabler-edit"></i> {{ __('admin.edit') }}
            </a>
        </li>
        <li>
            <a href="javascript:void(0)" class="dropdown-item delete-btn"
                data-id="{{ $row->id }}"
                data-url="{{ $deleteUrl }}"
                data-table="#region-table"
                title="{{ __('admin.delete') }}">
                <i class="icon-base ti tabler-trash"></i> {{ __('admin.delete') }}
            </a>
        </li>
    </ul>
</div>
