<?php

namespace Rappasoft\LaravelLivewireTables\Filters;

use Carbon\Carbon;

class DateFilter extends Filter
{
    public Carbon $minDate;
    public Carbon $maxDate;

    public static function make(string $name, string $component = 'livewire-tables::table.filters.date-filter'): DateFilter
    {
        return new static($name, $component, self::STRING_VALUE_TYPE);
    }

    public function setMinDate(Carbon $date): DateFilter
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

    public function setMaxDate(Carbon $date): DateFilter
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
