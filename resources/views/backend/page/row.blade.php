<x-livewire-tables::bs4.table.cell>
    @if($row->published_status == 0)
   <a target="_blank" href="{{ route('admin.page-preview', ['slug' => $row->slug, 'preview' => '1']) }}">{{ $row->title }}</a>
    @else
    <a target="_blank" href="{{ route('frontend.single-page',['slug'=>$row->slug]) }}">{{ $row->title }}</a>
    @endif
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->post_status }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->created_at }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->updated_at }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @include('backend.datatable.action-edit', ['route' => route('admin.pages.edit', ['page' => $row])])
    @include('backend.datatable.action-delete', ['route' => route('admin.pages.destroy', ['page' => $row])])
</x-livewire-tables::bs4.table.cell>
