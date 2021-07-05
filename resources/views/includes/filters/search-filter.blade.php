<x-input
    id="{{$filterId}}_filter"
    label="{{$filter->label()}}"
    wire:model.debounce.350ms="filters.{{ $filterId }}"
    placeholder="Wpisz..."
    type="text"
    class="w-64 shadow-sm border-cool-gray-300 block transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo @if (isset($filters['search']) && strlen($filters['search'])) rounded-none rounded-l-md @else rounded-md @endif"
>
    @if (isset($filters[$filterId]) && strlen($filters[$filterId]))
        <x-slot name="append">

            <div class="absolute inset-y-0 right-0 flex items-center">
                <button
                    class="h-full rounded-tr-md p-3 rounded-br-md focus:outline-none focus:ring-2 flex items-center ring-indigo-600">
                    <svg wire:click="$set('filters.{{$filterId}}', null)" xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </x-slot>
    @endif
</x-input>

