<?php


namespace Rappasoft\LaravelLivewireTables\Filters;


class SelectFilter extends Filter
{
    public string $optionLabel = 'label';
    public string $optionValue = 'value';

    /**
     * @var array
     */
    public array $options = [];

    /**
     * @param string $name
     * @param string $component
     * @return SelectFilter
     */
        public static function make(string $name, string $component = 'livewire-tables::includes.filters.select-filter'): SelectFilter
    {
        return new static($name, $component, self::STRING_VALUE_TYPE);
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options): SelectFilter
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

    /**
     * @param $raw
     * @return int|string|null
     */
    public function processValue($raw)
    {
        return $this->hasIntegerKeys() ? (int)$raw : trim($raw);
    }

    /**
     * Check whether the filter has numeric keys or not
     *
     * @param string $filter
     *
     * @return bool
     */
    public function hasIntegerKeys(): bool
    {
        return is_int($this->options()[0] ?? null);
    }

    public function getOptionLabel($value)
    {
        $option =collect($this->options())->where($this->optionValue,'=',$value)->first();
        if ($option) {
            return $option->label;
        } else {
            return $value;
        }
    }

    public function allowedValue($raw): bool
    {
        return collect($this->options())->where($this->optionValue, '=', $raw)->count() > 0;
    }

    public function formatPill($value): string
    {
        return $this->getOptionLabel($value);
    }
}
