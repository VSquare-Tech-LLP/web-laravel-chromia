<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class RolesTable extends DataTableComponent
{
    public string $tableName = 'roles';
//    protected $model = Role::class;
    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        return Role::query()->select(['*'])->with('permissions:id,name,description')
            ->withCount('users')
            ->when($this->getAppliedFilterWithValue('search'), fn ($query, $term) => $query->search($term));
    }

    public function columns(): array
    {
        return [
            Column::make(__('Type'))
                ->format(
                    function($value, $row, Column $column) {
                        if ($row->type === \App\Domains\Auth\Models\User::TYPE_ADMIN)
                            return 'Administrator';
                        elseif ($row->type === \App\Domains\Auth\Models\User::TYPE_USER)
                            return 'User';
                        else
                            return "N/A";
                    }
                )
                ->sortable(),
            Column::make(__('Name'))
                ->searchable()
                ->sortable(),
            Column::make(__('Permissions'))
                ->label(
                    fn($row, Column $column) => $row->permissions_label
                ),
            Column::make(__('Number of Users'))
                    ->label(fn ($row) => $row->users_count)
                    ->sortable(),

            Column::make('Actions')
                ->label(
                    fn($row, Column $column) => view('backend.auth.role.includes.actions')->withModel($row)
                )
                ->unclickable(),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setDefaultSort('id', 'desc')->setPerPageAccepted([25, 50, 100])->setPerPage(50);
    }
}
