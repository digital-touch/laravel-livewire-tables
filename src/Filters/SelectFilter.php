<?php


namespace Rappasoft\LaravelLivewireTables\Filters;


class SelectFilter extends Filter
{
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

    public function allowedValue($raw): bool
    {
        foreach ($this->options() as $optionValue) {
            // If the option is an integer, typecast filter value
            if (is_int($optionValue) && $optionValue === (int)$raw) {
                return true;
            }

            // Strict check the value
            if ($optionValue === $raw) {
                return true;
            }
        }

        return false;
    }
}
