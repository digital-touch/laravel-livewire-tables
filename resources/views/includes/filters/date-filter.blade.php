<x-input
    id="{{$filterId}}_filter"
    type="date"
    label="{{ $filter->label() }}"
    wire:model="filters.{{ $filterId }}"
    placeholder="Data"
/>
