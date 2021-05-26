@if ($showSorting && count($sorts))

    <div wire:key="sort-badges" class="p-4 md:p-0">

        <small class="text-gray-700">Zastosowane sortowania:</small>

        @foreach($sorts as $col => $dir)
            @include('livewire-tables::includes.sort-pill')
        @endforeach

        <button title="Usuń wszystkie"
            wire:click.prevent="resetSorts"
            class="focus:outline-none active:outline-none"
        >
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                @lang('Clear')
            </span>
        </button>
    </div>
@endif
