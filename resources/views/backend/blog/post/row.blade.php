<x-livewire-tables::bs4.table.cell>
    @if($row->published_status == 1)
    <a target="_blank" href="{{route('frontend.single-post',['slug'=>$row->slug])}}">{{ $row->title }}</a>
    @else
    <a target="_blank" href="{{route('admin.post-preview',['slug'=>$row->slug])}}">{{ $row->title }}</a>
    @endif
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->category_names }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->mainCategory->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->user->name }}
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
    @include('backend.datatable.action-edit', ['route' => route('admin.posts.edit', ['post' => $row])])
    @include('backend.datatable.action-delete', ['route' => route('admin.posts.destroy', ['post' => $row])])
</x-livewire-tables::bs4.table.cell>
