<?php

namespace Rappasoft\LaravelLivewireTables;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Traits\WithBulkActions;
use Rappasoft\LaravelLivewireTables\Traits\WithCustomPagination;
use Rappasoft\LaravelLivewireTables\Traits\WithFilters;
use Rappasoft\LaravelLivewireTables\Traits\WithPerPagePagination;
use Rappasoft\LaravelLivewireTables\Traits\WithSearch;
use Rappasoft\LaravelLivewireTables\Traits\WithSorting;

/**
 * Class TableComponent.
 *
 * @property LengthAwarePaginator|Collection|null $rows
 * @property Collection|null $summaryRows
 */
abstract class DataTableComponent extends Component
{
    use WithBulkActions;
    use WithCustomPagination;
    use WithFilters;
    use WithPerPagePagination;
    use WithSorting;

    /**
     * Whether or not to refresh the table at a certain interval
     * false is off
     * If it's an integer it will be treated as milliseconds (2000 = refresh every 2 seconds)
     * If it's a string it will call that function every 5 seconds unless it is 'keep-alive' or 'visible'.
     *
     * @var bool|string
     */
    public $refresh = false;

    /**
     * Whether or not to display an offline message when there is no connection.
     *
     * @var bool
     */
    public bool $offlineIndicator = true;

    /**
     * The message to show when there are no results from a search or query
     *
     * @var string
     */
    public string $emptyMessage = 'No items found. Try narrowing your search.';

    /**
     * Name of the page parameter for pagination
     * Good to change the default if you have more than one datatable on a page.
     *
     * @var string
     */
    protected string $pageName = 'page';

    /**
     * Unique name to use for this table if you want the 'per page' options to be remembered on a per table basis.
     * If not, all 'per page' stored in the session will default to the same option for every table with this default name.
     *
     * I.e. If the users changes it to 25 on the users table, the roles table will also default to 25, unless they have unique tableName's
     *
     * @var string
     */
    protected string $tableName = 'table';

    /**
     * @var \null[][]
     */
    protected $queryString = [
        'filters' => ['except' => null],
        'sorts' => ['except' => null],
    ];

    /**
     * @var string[]
     */
    protected $listeners = ['refreshDatatable' => '$refresh'];

    /**
     * The array defining the columns of the table.
     *
     * @return array
     */
    abstract public function columns(): array;

    /**
     * The base query with search and filters for the table.
     *
     * @return Builder|Relation
     */
    abstract public function query();

    /**
     * TableComponent constructor.
     *
     * @param null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function collection(): ?Collection
    {
        return collect();
    }

    /**
     * Get the rows query builder with sorting applied.
     *
     * @return Builder|Relation
     */
    public function rowsQuery()
    {
        $this->purgeFiltersValues();

        $query = $this->query();

        if ($query) {
            if (method_exists($this, 'applySorting')) {
                $query = $this->applySorting($query);
            }

            if (method_exists($this, 'applySearchFilter')) {
                $query = $this->applySearchFilter($query);
            }
        }

        return $query;
    }

    public function getSummaryRowsProperty(): Collection
    {
        return collect();
    }

    /**
     * Get the rows paginated collection that will be returned to the view.
     *
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function getRowsProperty()
    {
        $query = $this->rowsQuery();

        if ($query) {
            if ($this->paginationEnabled) {
                return $this->applyPagination($query);
            } else {
                return $query->get();
            }
        } else {
            if ($this->paginationEnabled) {
                return $this->paginateCollection($this->collection(), $this->perPage);
            } else {
                return $this->collection();
            }
        }
    }

    /**
     * Reset all the criteria
     */
    public function resetAll(): void
    {
        $this->resetFilters();
        $this->resetSorts();
        $this->resetBulk();
        $this->resetPage();
    }

    /**
     * The view to render each row of the table.
     *
     * @return string
     */
    public function rowView(): string
    {
        return 'livewire-tables::components.table.row-columns';
    }

    public function summaryRowView(): string
    {
        return 'livewire-tables::components.table.row-columns';
    }

    /**
     * @return mixed
     */
    public function render()
    {
        return view('livewire-tables::datatable')
            ->with([
                'columns' => $this->columns(),
                'rowView' => $this->rowView(),
                'filtersView' => $this->filtersView(),
                'customFilters' => $this->filters(),
                'rows' => $this->rows,
                'summaryRows' => $this->summaryRows,
                'summaryRowView' => $this->summaryRowView(),
            ]);
    }

    /**
     * Get a column object by its field
     *
     * @param string $column
     *
     * @return mixed
     */
    protected function getColumn(string $column)
    {
        return collect($this->columns())
            ->where('column', $column)
            ->first();
    }

    public function getKey($row)
    {
        return $row->getKey();
    }

    public function getSummaryKey($summaryRow)
    {
        return $summaryRow->getKey();
    }

    public function paginateCollection(Collection $results, $perPage = 15, $columns = [], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $total = $results->count();

        if (count($columns)) {
            $results = $total ? $this->forPage($results, $page, $perPage)->only($columns) : collect();
        } else {
            $results = $total ? $this->forPage($results, $page, $perPage) : collect();
        }

        return $this->collectionPaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }

    /**
     * Set the limit and offset for a given page.
     *
     * @param int $page
     * @param int $perPage
     * @return Collection
     */
    public static function forPage(Collection $results, int $page, int $perPage = 15): Collection
    {
        return $results->skip(($page - 1) * $perPage)->take($perPage);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param \Illuminate\Support\Collection $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     * @param array $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected static function collectionPaginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }
}
