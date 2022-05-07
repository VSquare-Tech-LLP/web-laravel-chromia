<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @include('backend.datatable.action-edit', ['route' => route('admin.tags.edit', ['tag' => $row])])
    @include('backend.datatable.action-delete', ['route' => route('admin.tags.destroy', ['tag' => $row])])
</x-livewire-tables::bs4.table.cell>
