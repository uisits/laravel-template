@php
    $setting = App\Models\Setting::first();
@endphp

<x-filament-widgets::widget>
    @if($setting)
        <x-filament::section>
            <x-slot name="heading">
                Current Term: {{ $setting->term_cd_desc }}
            </x-slot>

            <x-slot name="description">
                Term Code: {{ $setting->term_cd }}
            </x-slot>

            {{ $setting->home_page_msg }}
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
