<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <role-index :user="{{ auth()->user() }}" :roles="{{ $roles }}"></role-index>
    </div>
</x-app-layout>
