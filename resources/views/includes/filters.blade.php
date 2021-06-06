@if ($filtersView || count($customFilters))

    @if ($filtersView)
        @include($filtersView)
    @endif

    @if (count($customFilters))
        @foreach ($customFilters as $filterId => $filter)
            <div class="block text-sm text-gray-700 {{$filter->width}}">
                {{--@php($selected = $this->hasFilter($filterId) ? $filters[$filterId]:null)--}}
                @include($filter->component())
            </div>
        @endforeach
    @endif

    @if (count(array_filter($filters)) && !(count(array_filter($filters)) === 1))
        <x-button wire:click.prevent="resetFilters" type="button">
            @lang('Clear')
        </x-button>
    @endif

@endif
