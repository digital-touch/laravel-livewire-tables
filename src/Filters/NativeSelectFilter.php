<?php


namespace Rappasoft\LaravelLivewireTables\Filters;


class NativeSelectFilter extends SelectFilter
{
    /**
     * @param string $name
     * @param string $component
     * @return SelectFilter
     */
        public static function make(string $name, string $component = 'livewire-tables::includes.filters.native-select-filter'): SelectFilter
    {
        return new static($name, $component, self::STRING_VALUE_TYPE);
    }
}
