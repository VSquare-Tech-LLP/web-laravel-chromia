<?php


namespace App\Http\Livewire\Backend\Blog;

use App\Models\Blog\Category;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;


class CategoriesTable extends DataTableComponent
{

    public string $defaultSortColumn = 'id';
    public string $defaultSortDirection = 'desc';

    public function query(): Builder
    {
        return Category::when($this->getFilter('search'), fn($query, $term) => $query->search($term));
    }

    public function columns(): array
    {
        return [
            Column::make(__('Name'), 'name')
                ->sortable()
                ->searchable(),

            Column::make(__('Parent Name')),

            Column::make(__('Actions')),
        ];

    }

    public function rowView(): string
    {
        return 'backend.blog.category.row';
    }
}
