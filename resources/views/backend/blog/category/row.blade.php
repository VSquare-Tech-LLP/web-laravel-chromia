<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @if($row->parent_id)
    {{ $row->parent->name }}
    @else
    None
    @endif
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @include('backend.datatable.action-edit', ['route' => route('admin.categories.edit', ['category' => $row])])
    @include('backend.datatable.action-delete', ['route' => route('admin.categories.destroy', ['category' => $row])])
</x-livewire-tables::bs4.table.cell>
