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
        $this->reset('filters');
    }

    /**
     * Runs when any filter is changed
     */
    public function updatedFilters(): void
    {
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

    public function filter($id): Filter
    {
        return $this->filters()[$id];
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
    public function purgeFiltersValues(): void
    {
        // Filter $filters values
        $this->filters = collect($this->filters)->filter(function ($filterValue, $filterId) {

            $filterDefinitions = $this->filters();

            // Filter out any keys that weren't defined as a filter
            if (!isset($filterDefinitions[$filterId])) {
                return false;
            }

            // Ignore null values
            if (is_null($filterValue)) {
                return true;
            }

            $filter = $filterDefinitions[$filterId];

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
     * @param string $filterId
     *
     * @return bool
     */
    public function hasFilter(string $filterId): bool
    {
        return isset($this->filters[$filterId])
            && $this->filters[$filterId] !== null
            && (
                (is_string($this->filters[$filterId]) && $this->filters[$filterId] !== '')
                || (is_array($this->filters[$filterId]) && count($this->filters[$filterId]))
            );
    }

    /**
     * Get the value of a given filter
     *
     * @param string $filterId
     *
     * @return null|int|string|array
     */
    public function getFilter(string $filterId)
    {
        if (!$this->hasFilter($filterId)) {
            return null;
        }

        if (!in_array($filterId, collect($this->filters())->keys()->toArray(), true)) {
            return null;
        }

        return $this->filters[$filterId];
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

    /**
     * Set a given filter to null
     *
     * @param $filterId
     */
    public function removeFilter($filterId, $value = null): void
    {
        if (isset($this->filters[$filterId])) {

            if (is_array($this->filters[$filterId])) {
                $index = array_search($value, $this->filters[$filterId]);
                if ($index !== false) {
                    array_splice($this->filters[$filterId], $index, 1);
                }
            } else {
                $this->filters[$filterId] = null;
            }
        }
    }
}
