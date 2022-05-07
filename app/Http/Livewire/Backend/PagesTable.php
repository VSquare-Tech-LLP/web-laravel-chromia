<?php


namespace App\Http\Livewire\Backend;

use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PagesTable extends DataTableComponent
{
    public function query(): Builder
    {
        return Post::withoutGlobalScope('post')->withoutGlobalScope('published')->page()
            ->when(
                $this->getFilter('search'),
                fn($query, $term) => $query->search($term)
            );
    }

    public function columns(): array
    {
        return [
            Column::make(__('Title'), 'title')
                ->sortable()
                ->searchable(),

            Column::make(__('Status'), 'published_status')
                ->sortable(),

            Column::make(__('Created On'), 'created_at')
                ->sortable(),

            Column::make(__('Updated On'), 'updated_at')
                ->sortable(),

            Column::make(__('Actions')),
        ];
    }

    public function rowView(): string
    {
        return 'backend.page.row';
    }
}
