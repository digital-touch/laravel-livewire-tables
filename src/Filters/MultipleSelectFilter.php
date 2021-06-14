<?php

namespace Rappasoft\LaravelLivewireTables\Filters;

class MultipleSelectFilter extends Filter
{
    public string $optionLabel = 'label';
    public string $optionValue = 'value';

    public string $width = "w-96";

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

    public function getOptionLabel($value)
    {
        $option =collect($this->options())->where($this->optionValue,'=',$value)->first();
        if ($option) {
            return $this->label;
        } else {
            return $value;
        }
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

    public function formatPill($value): string
    {
        return $this->getOptionLabel($value);
    }
}
