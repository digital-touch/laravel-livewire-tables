@php($filter = $this->filter($filterId))

<span wire:key="filter-{{$filterId}}-{{ $valueItem }}"
      class="inline-flex items-center py-0.5 pl-2 pr-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">

    {{ $filter->label() }}: {{ $filter->formatPill($valueItem) }}

    <button title="Usuń"
        wire:click="removeFilter('{{$filterId}}','{{$valueItem}}')"
        type="button"
        class="flex-shrink-0 ml-0.5 h-4 w-4 rounded-full inline-flex items-center justify-center text-primary-400 hover:bg-primary-200 hover:text-primary-500 focus:outline-none focus:bg-primary-500 focus:text-white"
    >

        <span class="sr-only">@lang('Usuń opcje filtru')</span>
        <svg class="h-2 w-2" stroke="currentColor" fill="none" viewBox="0 0 8 8">
            <path stroke-linecap="round" stroke-width="1.5" d="M1 1l6 6m0-6L1 7"/>
        </svg>
    </button>
</span>
