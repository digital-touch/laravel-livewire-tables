<div class="container mx-auto"
>
    <div class="space-y-4"
         @if (is_numeric($refresh)) wire:poll.{{ $refresh }}ms
         @elseif(is_string($refresh)) wire:poll="{{ $refresh }}" @endif
    >

        {{ $slot ?? '' }}

        <div class="bg-white shadow-xl sm:rounded-lg p-4">

            <div class="bg-white shadow-xl sm:rounded-lg">
                @include('livewire-tables::includes.offline')
            </div>

            <div class="flex-col space-y-4">
                @include('livewire-tables::includes.sorting-pills')
                @include('livewire-tables::includes.filter-pills')

                <div class="md:flex md:justify-between p-4 md:p-0 items-end space-x-4">
                    {{--<div class="w-full mb-4 md:mb-0 md:w-2/4 md:flex space-y-4 md:space-y-0 md:space-x-4">--}}
                        @include('livewire-tables::includes.search')
                        @include('livewire-tables::includes.filters')
                        @isset($toolsView)
                            @include($toolsView)
                        @endisset
                    <div class="flex-1"></div>
                    {{--</div>--}}

                    {{--<div class="md:items-end md:mb-0 md:flex space-y-4 md:space-y-0 md:space-x-4">--}}
                    {{--<div class="md:space-x-2 md:flex md:items-end">--}}
                        @include('livewire-tables::includes.bulk-actions')

                        {{--<div class="w-full md:w-auto">--}}
                            @include('livewire-tables::includes.per-page')
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>

        <div class="bg-white shadow-xl sm:rounded-lg">
            <div class="flex-col ">

                @include('livewire-tables::includes.table')
                @include('livewire-tables::includes.pagination')

            </div>
        </div>
    </div>
</div>
