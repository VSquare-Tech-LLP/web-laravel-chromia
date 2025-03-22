<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\User;
use App\Domains\Auth\Models\Pack;
use App\Domains\Auth\Models\Image;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class UsersTable.
 */
class ImageTable extends DataTableComponent
{

    public string $tableName = 'photos';
    protected $index = 0;

    /**
     * @var
     */
    public $status;

    /**
     * @var array|string[]
     */
    public array $sortNames = [
        'email_verified_at' => 'Verified',
    ];

    /**
     * @var array|string[]
     */
    public array $filterNames = [
        'type' => 'User Type',
        'verified' => 'E-mail Verified',
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function builder(): Builder
    {
        $query = Image::query()
            ->select('photos.*')
            ->with('category:id,name');

        return $query;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        /* $packlist = Pack::select('id','packname')->get()->toArray();
        
        $packlistwrap = [];
        foreach($packlist as $packitem){
            $packlistwrap[$packitem['id']] = $packitem['packname'];
        }
       
        return [
            SelectFilter::make('Pack', 'pack_id')
                ->options($packlistwrap)
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('memeimages.pack_id', $value);
                }),
        ]; */

        return [];
    }

    public function columns(): array
    {
        $this->index = $this->page > 1 ? ($this->page - 1) * $this->perPage : 0;

        return [
            Column::make(__('No.'))->label(fn ($row, Column $column) => ++$this->index)->unclickable(),
            //Column::make(__('Thumb'),'thumbnail'),
            Column::make(__('Category'),'category.name')->searchable()->sortable(),
            Column::make(__(' '))
                ->label(function ($row, Column $column) {
                    //$thumbnail = url("storage/source_images/".$row->image);
                    $thumbnail = $row->url;
                    return '<img width="50" src="'.$thumbnail.'">';
                })->html(),
            Column::make('Actions')
                ->label(function($row, Column $column){
                    //return json_encode($row);
                    return view('backend.images.actions',['image'=>$row]);
                })
                /* ->label(
                    fn($row, Column $column) => view('backend.memes.actions',['meme'=>$row->id])
                    fn($row, Column $column) =>  dd($row)
                ) */
                ->unclickable(),

        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')->setDefaultSort('photos.id', 'desc')->setPerPageAccepted([25, 50, 100])->setPerPage(50);
    }
}
