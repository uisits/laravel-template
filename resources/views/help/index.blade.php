<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Petitions Help') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h1 class="text-center m-2 p-2 text-2xl text-gray-800">
                    For assistance, please contact ITS Client Services at <a href="mailto:techsupport@uis.edu?subject={{ env('APP_NAME') }} Help">techsupport@uis.edu</a>
                </h1>
            </div>
        </div>
    </div>
</x-app-layout>
