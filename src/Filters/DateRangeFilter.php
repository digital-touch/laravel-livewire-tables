<?php

namespace Rappasoft\LaravelLivewireTables\Filters;

use Carbon\Carbon;

class DateRangeFilter extends Filter
{
    public Carbon $minDate;
    public Carbon $maxDate;

    public static function make(string $name, string $component = 'livewire-tables::table.filters.date-range-filter'): DateRangeFilter
    {
        return new static($name, $component, self::ARRAY_VALUE_TYPE);
    }

    public function setMinDate(Carbon $date): DateRangeFilter
    {
        $this->minDate = $date;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function minDate(): Carbon
    {
        return $this->minDate;
    }

    public function setMaxDate(Carbon $date): DateRangeFilter
    {
        $this->maxDate = $date;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function maxDate(): Carbon
    {
        return $this->maxDate;
    }
}
