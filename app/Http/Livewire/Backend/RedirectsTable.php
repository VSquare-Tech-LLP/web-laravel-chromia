<?php

namespace App\Http\Livewire\Backend;

use App\Models\Redirect;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class RedirectsTable extends DataTableComponent
{
    public bool $showSearch = false;
    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return Redirect::query();
    }

    public function columns(): array
    {
        return [
            Column::make(__('From URL')),

            Column::make(__('To URL')),

            Column::make(__('Status')),

            Column::make(__('Update')),

            Column::make(__('Delete')),

        ];
    }

    public function rowView(): string
    {
        return 'backend.redirect.row';
    }
}
