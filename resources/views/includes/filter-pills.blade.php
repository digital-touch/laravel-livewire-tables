@if ($showFilters && count(array_filter($filters)) && !(count(array_filter($filters)) === 1 && isset($filters['search'])))

    <div wire:key="filter-badges" class="p-4 md:p-0">

        <small class="text-gray-700">Zastosowane filtry:</small>

        @foreach($filters as $filterId => $filterValue)

            @if($filterId === 'search')

                @continue

            @elseif (is_string($filterValue) && strlen($filterValue))

                @include('livewire-tables::includes.pill')

            @elseif (is_array($filterValue) && count($filterValue))

                @foreach($filterValue as $valueItem)

                    @include('livewire-tables::includes.multi-pill')

                @endforeach

            @endif
        @endforeach

        <button class="focus:outline-none active:outline-none" wire:click.prevent="resetFilters"
                title="Usuń wszystkie">
            <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                @lang('Clear')
            </span>
        </button>

    </div>

@endif
