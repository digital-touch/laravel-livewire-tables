<?php


namespace Rappasoft\LaravelLivewireTables\Traits;


trait WithSelectFilter
{
    /**
     * Set a given filter to null
     *
     * @param $filter
     */
    public function removeFilterValue($filter, $index): void
    {
        if (isset($this->filters[$filter])) {
            unset($this->filters[$filter][$index]);
        }
    }

    public function selectFilterValue($filterName, $optionKey)
    {
        if ($optionKey && $this->hasFilter($filterName)) {
                $this->filters[$filterName] = null;
        }

        if ($optionKey) {
            $this->filters[$filterName] = $optionKey;
        }

        $this->emitSelf('$refresh');
    }
}
