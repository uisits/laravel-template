@php
$registrations = \App\Models\Registration::with('evaluation')
    ->where('student_netid', auth()->user()->netid)
    ->get();
@endphp
<x-filament-widgets::widget>
    @if($registrations)
        <x-filament::section>
            <x-slot name="heading">
                Evaluations
            </x-slot>

            <x-slot name="description">
                Your evaluations status
            </x-slot>

            <div>
                <div class="mt-8 flow-root overflow-hidden">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <table class="w-full text-left">
                            <thead class="">
                            <tr>
                                <th scope="col" class="relative isolate py-3.5 pr-3 text-left text-sm font-semibold text-gray-900">
                                    Course
                                    <div class="absolute inset-y-0 right-full -z-10 w-screen border-b border-b-gray-200"></div>
                                    <div class="absolute inset-y-0 left-0 -z-10 w-screen border-b border-b-gray-200"></div>
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                    Instructor
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 sm:table-cell">
                                    Section Type
                                </th>
                                <th scope="col" class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 md:table-cell">
                                    Evaluation Start
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Evaluation End</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($registrations as $registration)
                                <tr>
                                    <td class="px-2 whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900">{{ $registration->course_name }}</div>
                                                <div class="mt-1 text-gray-500">{{ $registration->course_no }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 whitespace-nowrap py-5 pl-4 pr-3 text-sm sm:pl-0">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <a href="mailto:{{$registration->instructor_netid}}" class="font-medium text-gray-900">{{ $registration->instructor_name }}</a>
                                                <div class="mt-1 text-gray-500">{{ $registration->instructor_uin }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-2 hidden px-3 py-4 text-sm text-gray-500 md:table-cell">
                                        {{ $registration->section_type }}
                                    </td>
                                    <td class="px-2 py-4 text-sm text-gray-500">{{ $registration->evaluation_start->format('j F Y h:i A') }}</td>
                                    <td class="px-2 py-4 text-sm text-gray-500">{{ $registration->evaluation_end->format('j F Y h:i A') }}</td>
                                    <td class="px-2 py-4 pl-3 text-sm font-medium">
                                        @if($registration->evaluation_start >= \Carbon\Carbon::now() || $registration->evaluation_end <= \Carbon\Carbon::now())
                                            Not Available
                                        @else
                                            <span
                                                @class([
                                                    'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                                    'bg-green-500 text-green-400 ring-green-500' => $registration->completed_evaluation,
                                                    'bg-yellow-500 text-yellow-400 ring-yellow-500' => ! $registration->completed_evaluation,
                                                ])>
                                                {{ $registration->completed_evaluation ? 'Completed' : 'Pending' }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-filament::section>
    @endif
</x-filament-widgets::widget>
