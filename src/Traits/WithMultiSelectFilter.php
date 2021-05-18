<?php


namespace Rappasoft\LaravelLivewireTables\Traits;


trait WithMultiSelectFilter
{
    /**
     * Set a given filter to null
     *
     * @param $filter
     */
    public function removeMultiFilter($filter, $index): void
    {
        if (isset($this->filters[$filter])) {
            unset($this->filters[$filter][$index]);
        }
    }


    public function selectFilterValues($filterKey, $optionKey)
    {
        if ($optionKey && $this->filters[$filterKey] && in_array($optionKey, $this->filters[$filterKey])) {
            $index = array_search($optionKey, $this->filters[$filterKey]);
            array_splice($this->filters[$filterKey], $index, 1);
            if (!count($this->filters[$filterKey])) {
                $this->filters[$filterKey] = null;
            }
        }

        if ($optionKey) {
            if (!$this->filters[$filterKey]) {
                $this->filters[$filterKey] = [];
            }
            $this->filters[$filterKey][] = $optionKey;
        }

        $this->emitSelf('refreshDatatable');
    }
}
