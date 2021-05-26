<?php


namespace Rappasoft\LaravelLivewireTables\Filters;


class SearchFilter extends Filter
{
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
}
