<?php


namespace Rappasoft\LaravelLivewireTables\Filters;


class SearchFilter extends Filter
{
    /**
     * @var int|null
     */
    public ?int $searchFilterDebounce = null;

    /**
     * @var bool|null
     */
    public ?bool $searchFilterDefer = null;

    /**
     * @var bool|null
     */
    public ?bool $searchFilterLazy = null;

    /**
     * Clear the search filter specifically
     */
    public function resetSearch(): void
    {
        $this->filters['search'] = null;
    }

    public static function make(string $name, string $component = 'livewire-tables::includes.filters.search-filter'): SearchFilter
    {
        return new static($name, $component, self::STRING_VALUE_TYPE);
    }

    public function processValue($raw)
    {
        return trim($raw);
    }

    public function allowedValue($raw): bool
    {
        return true;
    }

    public function getSearchFilterOptionsProperty(): string
    {
        if ($this->searchFilterDebounce) {
            return '.debounce.' . $this->searchFilterDebounce . 'ms';
        }

        if ($this->searchFilterDefer) {
            return '.defer';
        }

        if ($this->searchFilterLazy) {
            return '.lazy';
        }

        return '';
    }
}
