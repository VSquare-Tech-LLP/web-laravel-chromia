<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\User;
use App\Domains\Flux\Models\Log;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

/**
 * Class SwapLogsTable.
 */
class SwapLogsTable extends DataTableComponent
{

  public string $tableName = 'logs';

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
    // 'type' => 'User Type',
    // 'verified' => 'E-mail Verified',
  ];

  /**
   * @param  string  $status
   */
  // public function mount($status = 'active'): void
  // {
  //   $this->status = $status;
  // }

  /**
   * @return Builder
   */
  public function builder(): Builder
  {
    $query = Log::query()->selectRaw('*');

    // if ($this->status === 'deleted') {
    //   $query = $query->onlyTrashed();
    // } elseif ($this->status === 'deactivated') {
    //   $query = $query->onlyDeactivated();
    // } else {
    //   $query = $query->onlyActive();
    // }

    return $query;
  }

  /**
   * @return array
   */
  public function filters(): array
  {
    return [
      // SelectFilter::make('User Type', 'type')
      //   ->options([
      //     '' => 'Any',
      //     User::TYPE_ADMIN => 'Administrators',
      //     User::TYPE_USER => 'Users',
      //   ])
      //   ->filter(function (Builder $builder, string $value) {
      //     $builder->where('type', $value);
      //   }),
      // SelectFilter::make('Active', 'active')
      //   ->setFilterPillValues([
      //     '1' => 'Active',
      //     '0' => 'Inactive',
      //   ])
      //   ->options([
      //     '' => 'All',
      //     '1' => 'Yes',
      //     '0' => 'No',
      //   ])
      //   ->filter(function (Builder $builder, string $value) {
      //     if ($value === '1') {
      //       $builder->where('active', true);
      //     } elseif ($value === '0') {
      //       $builder->where('active', false);
      //     }
      //   }),
      // SelectFilter::make('E-mail Verified', 'verified')
      //   ->options([
      //     '' => 'Any',
      //     'yes' => 'Yes',
      //     'no' => 'No',
      //   ])->filter(function (Builder $builder, string $value) {
      //     if ($value === 'yes') {
      //       $builder->whereNotNull('email_verified_at');
      //     } elseif ($value === 'no') {
      //       $builder->whereNull('email_verified_at');
      //     }
      //   }),
    ];
  }

  /**
   * @return array
   */
  public function columns(): array
  {
    return [
      // Column::make(__('Type'))
      //   ->format(
      //     fn ($value, $row, Column $column) => view('backend.auth.user.includes.type', ['user' => $row])->withValue($value)
      //   )
      //   ->searchable()
      //   ->sortable(),
      Column::make(__('Date'), 'created_at')
        ->format(
          fn ($value, $row, Column $column) => $value->format('M-d h:i:s A')
        )
        ->searchable()
        ->sortable(),
      Column::make(__('Ip'), 'ip_address')
        ->searchable()
        ->sortable(),
      Column::make(__('Device Id'), 'device_id')
        ->searchable(),
      Column::make(__('Prompt'), 'prompt')
        ->searchable(),
      Column::make(__('Settings'), 'settings')
        ->format(function ($value, $column, $row) {
          // return '<a href="' . $value . '" target="_blank"><img src="' . $value . '" alt="Targer Image" width="100" height="100"></a>';
          $out = "<ul>";
          foreach(json_decode($value,false) as $setting){
            $out.='<li>'.$setting.'</li>';
          } 
          return $out.'</ul>';
        })->html(),
      Column::make(__('Results'), 'results')
        ->format(function ($value, $column, $row) {
          if (is_null($value)) {
            return '<p>Not Generated yet.</p>';
          }
          // return '<a href="' . $value . '" target="_blank"><img src="' . $value . '" alt="Result Image" width="100" height="100"></a>';
          $out ="";
          foreach(json_decode($value) as $image) {
            $out.= '<a href="' . $image . '" target="_blank"><img src="' . $image . '" alt="Result Image" width="100" height="100"></a>';
          }
          return $out;
        })->html(),
      Column::make(__('Result Id'), 'result_id'),
      Column::make(__('Paid'), 'is_paid')->format(function ($row) {
        return $row == 1 ? __('Paid') : __('Free');
      })->searchable(function ($query, $term) {
        if (strtolower($term) == 'paid') {
          $query->where('is_paid', 1)->orWhere('is_paid', true);
        } elseif (strtolower($term) == 'free') {
          $query->Where('is_paid', false)->orwhere('is_paid', 0)->orWhereNull('is_paid');
        }
      }),
      // LinkColumn::make(__('E-mail'), 'email')
      //   ->title(fn ($row) => $row->email)
      //   ->location(fn ($row) => 'mailto:' . $row->eamil)
      //   ->attributes(fn ($row) => [
      //     'class' => 'text-decoration->none',
      //   ])
      //   ->sortable(),
      // Column::make(__('Verified'), 'email_verified_at')
      //   ->format(
      //     fn ($value, $row, Column $column) => view('backend.auth.user.includes.verified', ['user' => $row])->withValue($value)
      //   )
      //   ->sortable(),
      // Column::make(__('Roles'))
      //   ->label(
      //     fn ($row, Column $column) => $row->roles_label
      //   ),
      // Column::make(__('Additional Permissions'))
      //   ->label(
      //     fn ($row, Column $column) => $row->permissions_label
      //   ),
      // Column::make('Actions')
      //   ->label(
      //     fn ($row, Column $column) => view('backend.auth.user.includes.actions')->withUser($row)
      //   )
      //   ->unclickable(),

    ];
  }

  public function configure(): void
  {
    $this->setPrimaryKey('id')->setDefaultSort('id', 'desc')->setPerPageAccepted([25, 50, 100])->setPerPage(50);
  }
}
