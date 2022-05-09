<?php

namespace App\Http\Livewire\Backend;

use Illuminate\Database\Eloquent\Builder;
use Laravelista\Comments\Comment;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class CommentsTable extends DataTableComponent
{
    public bool $showSearch = false;

    private $status = [
        'all' => '',
        'pending' => 0,
        'approved' => 1,
    ];

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Comment::query();
        if (isset(request()->status)) {
            $query->where('approved', '=', $this->status[request()->status]);
        }
        return $query;
    }

    public function columns(): array
    {
        return [
            Column::make(__('Commenter')),

            Column::make(__('Comment')),

            Column::make(__('Post')),

            Column::make(__('Date')),

            Column::make(__('Action'))
        ];
    }

    public function rowView(): string
    {
        return 'backend.comments.row';
    }
}
