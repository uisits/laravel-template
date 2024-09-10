<x-guest-layout>
    <div class="">
        <div class="mt-1 border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
            <h3 class="text-sm font-semibold leading-6 text-gray-900">
                Evaluation Report for {{ $courseNo }} in {{ $semesterDesc }} semester.
            </h3>
            <h5 class="text-sm font-semibold leading-6 text-gray-900">
               Instructor: {{ $instructor }}
            </h5>
            <span>*Total number of students responding: {{ $totalCount }}</span>
            <div class="">
                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            1. Your current class standing:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="table table-striped min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Undergraduate</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Graduate</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_01', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_01', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_01', '2')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            2. Your sex:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Female</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Male</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_02', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_02', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_02', '2')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            3. Grade you expect to recieve in this class:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">No Response</th>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">A</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">B</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">C</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">D</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">U</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">I</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">CR</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">NC</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_03', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_03', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_03', '2')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_03', '3')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_03', '4')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_03', '5')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_03', '6')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_03', '7')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_03', '8')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            4. I took this course as:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">An Elective</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">A Program Requirement</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_04', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_04', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_04', '2')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            5. As a result of taking this course, my interest in this subject has:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Decreased</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Remained the same</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Increased</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_05', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_05', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_05', '2')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_05', '3')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            6. This course has increased my skills in critical thinking:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Yes</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">No</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_06', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_06', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_06', '2')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            7. The Instructor's presentation is well planned and organized:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Yes</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">No</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_07', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_07', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_07', '2')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            8. Do you think this teacher is competent in the content or material offered in this course:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Incompetent</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Somewhat incompetent</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Satisfactory</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Competent</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Exceptionally Competent</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Average Course Mean</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_08', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_08', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_08', '2')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_08', '3')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_08', '4')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_08', '5')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ round($evaluations->avg('answer_08'), 2) }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            9. This course has motivated me to work at my highest level:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Yes</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">No</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_09', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_09', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_09', '2')->count() }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="border-b border-gray-200 pb-5">
                    <div class="-ml-2 -mt-2 flex flex-wrap items-baseline">
                        <h3 class="ml-2 mt-2 text-sm leading-6 text-gray-900">
                            10. Overall, how do you rate the quality of this person as a teacher:
                        </h3>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">No Response</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Poor</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Fair</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Good</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Very Good</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Excellent</th>
                                        <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Average Course Mean</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                            {{ $evaluations->where('answer_10', '0')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_10', '1')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_10', '2')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_10', '3')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_10', '4')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $evaluations->where('answer_10', '5')->count() }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ round($evaluations->avg('answer_10'), 2) }}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <section>
                    <p class="text-muted">
                        *Please note: cross-listed and cross-taught courses, as well as lecture courses with multiple lab sections, may display a larger number of students responding than students registered in a given section.
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-guest-layout>
