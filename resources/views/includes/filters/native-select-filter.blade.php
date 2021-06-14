<x-native-select
    id="{{$filterId}}_filter"
    label="{{$filter->label()}}"
    :option-label="$filter->optionLabel"
    :option-value="$filter->optionValue"
    :options="$filter->options()"
    wire:model="filters.{{ $filterId }}"
/>
