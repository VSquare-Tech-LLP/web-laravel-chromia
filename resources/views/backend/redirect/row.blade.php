<x-forms.post :action="route('admin.redirects.update',['redirect' => $row])">
    @method('PUT')
    <x-livewire-tables::bs4.table.cell>
        <input type="text" class="form-control" name="from_url" value="{{$row->from_url }}">
    </x-livewire-tables::bs4.table.cell>

    <x-livewire-tables::bs4.table.cell>
        <input type="text" class="form-control" name="to_url" value="{{  $row->to_url }}">
    </x-livewire-tables::bs4.table.cell>

    <x-livewire-tables::bs4.table.cell>
        <select name="status_code" class="form-control">
            <option value="301" {{  $row->status_code == 301? 'selected': ''}}>301</option>
            <option value="302" {{  $row->status_code == 302? 'selected': ''}}>302</option>
        </select>
    </x-livewire-tables::bs4.table.cell>

    <x-livewire-tables::bs4.table.cell>
        <button type="submit" class="btn btn-info btn-sm">Update</button>
    </x-livewire-tables::bs4.table.cell>
</x-forms.post>

<x-livewire-tables::bs4.table.cell>
    @include('backend.datatable.action-delete', ['route' => route('admin.redirects.destroy', ['redirect' => $row])])
</x-livewire-tables::bs4.table.cell>
