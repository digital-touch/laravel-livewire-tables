<?php

namespace Rappasoft\LaravelLivewireTables;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * Class LaravelLivewireTablesServiceProvider.
 */
class LaravelLivewireTablesServiceProvider extends PackageServiceProvider
{
    public function bootingPackage(): void
    {
        /*Blade::componentNamespace('Livewire\\Tables', 'livewire-tables');*/
       /* Blade::component('livewire-tables::tailwind.components.table.table', 'livewire-tables::table');
        Blade::component('livewire-tables::tailwind.components.table.table', 'livewire-tables::table');
        Blade::component('livewire-tables::tailwind.components.table.heading', 'livewire-tables::table.heading');
        Blade::component('livewire-tables::tailwind.components.table.row', 'livewire-tables::table.row');
        Blade::component('livewire-tables::tailwind.components.table.cell', 'livewire-tables::table.cell');*/

    }

    /**
     * @param Package $package
     */
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-livewire-tables')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations();
    }
}
