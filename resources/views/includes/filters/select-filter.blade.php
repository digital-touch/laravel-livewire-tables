<label
    class="block text-sm font-medium leading-5 text-gray-700">
    {{ $filter->name() }}
</label>

<x-dropdown id="{{$filterName}}_filter" align="right"  wire:model="filters.{{ $filterName }}">
    <x-slot name="trigger">

        <button
            class="inline-flex justify-between items-center w-full rounded-md border border-gray-300 shadow-sm px-2 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo">
            <div>@if($selected){{  $selected }}@endif</div>

            <div class="ml-1">
                <svg class="fill-current h-6 w-6 min-w-6 min-h-6" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
        </button>

    </x-slot>

    <x-slot name="content">
        @foreach($filter->options() as $optionKey => $optionValue)
            @php($isSelected = isset($selected) && ($optionKey === $selected))
            <div wire:click="selectFilterValue('{{$filterName}}','{{$optionKey}}')"
                 x-on:refreshDatatable="{{$filterName}}_filter_open=false"
                 class="flex flex-row items-center justify-between px-4 py-2 {{$isSelected ? 'bg-gray-100':''}} cursor-pointer text-sm leading-5 text-gray-700 hover:bg-gray-200 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                 id="option-{{ $optionKey }}"><span>{{ $optionValue }}</span>
                @if($isSelected)
                    <svg class="h-4 w-4 min-w-4 min-h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                @endif
            </div>
        @endforeach
    </x-slot>
</x-dropdown>
