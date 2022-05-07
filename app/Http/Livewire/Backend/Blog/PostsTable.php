<?php


namespace App\Http\Livewire\Backend\Blog;


use App\Models\Blog\Post;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class PostsTable extends DataTableComponent
{
    public function filters(): array
    {
        return [
            'type' => Filter::make('Post Type')
                ->select([
                    '' => 'All',
                    'latest' => 'Latest',
                    'published' => 'Published',
                    'drafted' => 'Drafted',
                ]),
        ];
    }


    public function query(): Builder
    {
        $query = Post::query()->withoutGlobalScope('published');
        $query->when($this->getFilter('type') == 'latest', fn($query, $type) => $query->orderBy('updated_at', 'desc'));
        $query->when($this->getFilter('type') == 'published', fn($query, $type) => $query->where('published_status', 1));
        $query->when($this->getFilter('type') == 'drafted', fn($query, $type) => $query->where('published_status', 0));

        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make(__('Title'), 'title')
                ->sortable()
                ->searchable(),

            Column::make(__('Categories')),

            Column::make(__('Featured Category'), 'main_category')
                ->sortable()
                ->searchable(),

            Column::make(__('Author'), 'user_id')
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
        return 'backend.blog.post.row';
    }
}
