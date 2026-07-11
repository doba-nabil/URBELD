<a href="{{ route('regions.edit', $row->id) }}" class="btn btn-sm btn-primary">
    <i class="ti tabler-edit"></i>
</a>
<button class="btn btn-sm btn-danger delete-btn" data-url="{{ route('regions.destroy', $row->id) }}">
    <i class="ti tabler-trash"></i>
</button>
