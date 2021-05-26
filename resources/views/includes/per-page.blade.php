@if ($showPerPage)
    <x-native-select
        id="perPage"
        label="Wyników na stronę"
        :options="['10', '25', '50', '100']"
        wire:model="perPage"
    />
@endif
