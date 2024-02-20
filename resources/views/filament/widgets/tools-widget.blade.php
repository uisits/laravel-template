<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-wrench-screwdriver">
    <x-slot name="heading">
        Super Admin Tools
    </x-slot>

    <x-filament::button
        icon="heroicon-o-sparkles"
            href="{{ route('telescope') }}"
            target="_blank"
            label="Telescope"
            tooltip="Telescope"
            tag="a"
			badge-color="info">
            Telescope 
     </x-filament::button>

    <x-filament::button
        icon="heroicon-m-arrow-top-right-on-square"
            href="{{ route('pulse') }}"
            target="_blank"
            label="Pulse"
            tooltip="Pulse"
            tag="a"
			badge-color="info">
            Pulse
    </x-filament::button>

    </x-filament::section>
</x-filament-widgets::widget>
