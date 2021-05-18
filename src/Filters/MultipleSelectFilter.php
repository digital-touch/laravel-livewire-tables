<?php

namespace Rappasoft\LaravelLivewireTables\Filters;

class MultipleSelectFilter extends Filter
{
    /**
     * @var array
     */
    public array $options = [];

    public static function make(string $name, string $component = 'livewire-tables::includes.filters.multiple-select-filter'): MultipleSelectFilter
    {
        return new static($name, $component, self::ARRAY_VALUE_TYPE);
    }

    public function setOptions(array $options): MultipleSelectFilter
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    public function processValue($raw)
    {
        return $raw;
    }

    public function allowedValue($raw): bool
    {
        return true;
    }
}
