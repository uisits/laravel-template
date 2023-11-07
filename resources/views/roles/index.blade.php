<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Role Selection') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <roles-index :user="{{ auth()->user() }}" :roles="{{ json_encode($roles) }}"></roles-index>
    </div>
</x-app-layout>
