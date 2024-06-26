<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Auth\Models\User;
use App\Models\SwapLog;
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

  public string $tableName = 'swap_logs';

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
    $query = SwapLog::query()->selectRaw('*');

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
      Column::make(__('Source'), 'swap_source')
        ->format(function ($value, $column, $row) {
          return '<a href="' . $value . '" target="_blank"><img src="' . $value . '" alt="Source Image" width="100" height="100"></a>';
        })->html(),
      Column::make(__('Targer'), 'swap_target')
        ->format(function ($value, $column, $row) {
          return '<a href="' . $value . '" target="_blank"><img src="' . $value . '" alt="Targer Image" width="100" height="100"></a>';
        })->html(),
      Column::make(__('Result'), 'swap_result')
        ->format(function ($value, $column, $row) {
          return '<a href="' . $value . '" target="_blank"><img src="' . $value . '" alt="Result Image" width="100" height="100"></a>';
        })->html(),
      Column::make(__('Result Id'), 'swap_result_id'),
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
