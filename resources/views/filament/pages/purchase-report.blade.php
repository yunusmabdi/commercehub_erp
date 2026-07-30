<x-filament-panels::page>

    {{ $this->form }}

    <div class="mt-4 flex items-center gap-3">

        <x-filament::button
            wire:click="generateReport"
            icon="heroicon-m-magnifying-glass">

            Generate Report

        </x-filament::button>

        <x-filament::button
            wire:click="resetFilters"
            color="gray">

            Reset

        </x-filament::button>

    </div>

    <div class="mt-6">
        {{ $this->table }}
    </div>

</x-filament-panels::page>