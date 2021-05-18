<?php

namespace Rappasoft\LaravelLivewireTables\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Rappasoft\LaravelLivewireTables\Columns\Column;
use Rappasoft\LaravelLivewireTables\Filters\Filter;
use Rappasoft\LaravelLivewireTables\Utilities\ColumnUtilities;

/**
 * Trait WithFilters.
 */
trait WithFilters
{
    /**
     * Filter values
     *
     * @var array
     */
    public array $filters = [];

    /**
     * Map filter names
     *
     * @var array
     */
    public array $filterNames = [];

    /**
     * Default filters
     *
     * @var array|null[]
     */
    public array $baseFilters = [
        'search' => null,
    ];

    /**
     * Prebuild the $filters array
     */
    public function mountWithFilters(): void
    {
        $this->checkFilters();
    }

    /**
     * Reset the filters array but keep the value for search
     */
    public function resetFilters(): void
    {
        $search = $this->filters['search'] ?? null;

        $this->reset('filters');

        $this->filters['search'] = $search;
    }

    /**
     * Runs when any filter is changed
     */
    public function updatedFilters(): void
    {
        // Remove the search filter when it's empty
        if (isset($this->filters['search']) && $this->filters['search'] === '') {
            $this->resetSearch();
        }

        // Remove any url params that are empty
        $this->checkFilters();

        // Reset the page when filters are changed
        $this->resetPage();
    }

    /**
     * Define the filters array
     *
     * @return Filter[]
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Removes any filters that are empty
     */
    public function checkFilters(): void
    {
        foreach ($this->filters() as $filter => $_default) {
            if (!isset($this->filters[$filter]) || $this->filters[$filter] === '') {
                $this->filters[$filter] = null;
            }
        }
    }

    /**
     * Cleans $filter property of any values that don't exist
     * in the filter() definition.
     */
    public function allowFiltersValues(): void
    {
        // Filter $filters values
        $this->filters = collect($this->filters)->filter(function ($filterValue, $filterName) {
            $filterDefinitions = $this->filters();

            // Filter out any keys that weren't defined as a filter
            if (!isset($filterDefinitions[$filterName])) {
                return false;
            }

            // Ignore null values
            if (is_null($filterValue)) {
                return true;
            }

            $filter = $filterDefinitions[$filterName];

            return $filter->allowedValue($filterValue);
        })->toArray();
    }

    /**
     * Define the string location to a view to be included as the filters view
     *
     * @return string|null
     */
    public function filtersView(): ?string
    {
        return null;
    }

    /**
     * Check if a filter exists and isn't null
     *
     * @param string $filterName
     *
     * @return bool
     */
    public function hasFilter(string $filterName): bool
    {
        return isset($this->filters[$filterName])
            && $this->filters[$filterName] !== null
            && (
                (is_string($this->filters[$filterName]) && $this->filters[$filterName] !== '')
                || (is_array($this->filters[$filterName]) && count($this->filters[$filterName]))
            );
    }

    /**
     * Get the value of a given filter
     *
     * @param string $filterName
     *
     * @return int|string|null
     */
    public function getFilter(string $filterName)
    {
        if (!$this->hasFilter($filterName)) {
            return null;
        }
        if (!in_array($filterName, collect($this->filters())->keys()->toArray(), true)) {
            return null;
        }
        $filter = $this->filters()[$filterName];
        return $filter->processValue($this->filters[$filterName]);
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return collect($this->filters)
            ->reject(fn($value) => $value === null || $value === '')
            ->toArray();
    }

    /**
     * @return array
     */
    public function getFiltersWithoutSearch(): array
    {
        return collect($this->getFilters())
            ->reject(fn($_value, $key) => $key === 'search')
            ->toArray();
    }

    /**
     * Set a given filter to null
     *
     * @param $filter
     */
    public function removeFilter($filter): void
    {
        if (isset($this->filters[$filter])) {
            $this->filters[$filter] = null;
        }
    }

    /**
     * Collects columns with $searchable = true
     *
     * @return Column[]
     */
    public function getSearchableColumns(): array
    {
        return array_filter($this->columns(), fn(Column $column) => $column->isSearchable());
    }

    /**
     * Apply Search Filter
     *
     * @param Builder|Relation $query
     * @return Builder|Relation
     */
    public function applySearchFilter($query)
    {
        $searchableColumns = $this->getSearchableColumns();

        if ($this->hasFilter('search') && count($searchableColumns)) {
            $search = $this->getFilter('search');

            // Group search conditions together
            $query->where(function (Builder $subQuery) use ($search, $query, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    // Does this column have an alias or relation?
                    $hasRelation = ColumnUtilities::hasRelation($column->column());

                    // Let's try to map this column to a selected column
                    $selectedColumn = ColumnUtilities::mapToSelected($column->column(), $query);

                    // If the column has a search callback, just use that
                    if ($column->hasSearchCallback()) {
                        // Call the callback
                        ($column->getSearchCallback())($subQuery, $search);
                    } elseif (!$hasRelation || $selectedColumn) { // If the column isn't a relation or if it was previously selected
                        $whereColumn = $selectedColumn ?? $column->column();

                        // TODO: Skip Aggregates
                        if (!$hasRelation) {
                            $whereColumn = $query->getModel()->getTable() . '.' . $whereColumn;
                        }

                        // We can use a simple where clause
                        $subQuery->orWhere($whereColumn, 'like', '%' . $search . '%');
                    } else {
                        // Parse the column
                        $relationName = ColumnUtilities::parseRelation($column->column());
                        $fieldName = ColumnUtilities::parseField($column->column());

                        // We use whereHas which can work with unselected relations
                        $subQuery->orWhereHas($relationName, function (Builder $hasQuery) use ($fieldName, $search) {
                            $hasQuery->where($fieldName, 'like', '%' . $search . '%');
                        });
                    }
                }
            });
        }

        return $query;
    }
}
