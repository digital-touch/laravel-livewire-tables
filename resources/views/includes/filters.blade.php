@if ($filtersView || count($customFilters))

    <x-dropdown id="filters_dropdown" align="right" width="48">
        <x-slot name="trigger">
            <button
                class="flex items-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo">
                <div>
                    @isset($filtersLabel)
                        @lang($filtersLabel)
                    @else
                        @lang('Filters')
                    @endisset
                </div>

                <div class="ml-1">
                    <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            @if ($filtersView)
                @include($filtersView)
            @endif

            @if (count($customFilters))
                @foreach ($customFilters as $filterName => $filter)
                    <div class="py-1" role="none">
                        <div class="block px-4 py-2 text-sm text-gray-700" role="menuitem">
                            {{--@component($filter->component(),['filterName'=>$filterName,'filter'=>$filter])--}}
                            @php($selected = $this->hasFilter($filterName) ? $filters[$filterName]:null)
                            @include($filter->component())
                            {{--<x-dynamic-component :component="$filter->component()" :filterName="$filterName"
                                                 :selected="$this->hasFilter($filterName) ? $filters[$filterName]:null"
                                                 :filter="$filter"></x-dynamic-component>--}}
                        </div>
                    </div>
                @endforeach
            @endif

            @if (count(array_filter($filters)) && !(count(array_filter($filters)) === 1 && isset($filters['search'])))
                <div class="py-1" role="none">
                    <div class="block px-4 py-2 text-sm text-gray-700" role="menuitem">
                        <button
                            wire:click.prevent="resetFilters"
                            type="button"
                            class="w-full inline-flex items-center justify-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            @lang('Clear')
                        </button>
                    </div>
                </div>
            @endif
        </x-slot>
    </x-dropdown>

    {{--<div
        x-data="{ open: false }"
        @keydown.escape.stop="open = false"
        @click.away="open = false"
        class="relative block md:inline-block text-left"
    >
        <div>
            <button
                type="button"
                class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo"
                id="filters-menu" @click="open = !open" aria-haspopup="true" x-bind:aria-expanded="open"
                aria-expanded="true">


                @if (count(array_filter($filters)) && !(count(array_filter($filters)) === 1 && isset($filters['search'])))
                    <span
                        class="ml-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-indigo-100 text-indigo-800 capitalize">
                       {{ isset($filters['search']) ? count(array_filter($filters)) - 1 : count(array_filter($filters)) }}
                    </span>
                @endif


            </button>
        </div>

        <div
            x-cloak
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="block origin-top-right absolute left-0 mt-2 w-full md:w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-20"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="filters-menu"
        >

        </div>
    </div>--}}
@endif
